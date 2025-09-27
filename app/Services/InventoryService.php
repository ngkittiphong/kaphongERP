<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\WarehouseProduct;
use App\Models\MoveType;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class InventoryService
{
    /**
     * Move type constants for better readability
     */
    const MOVE_TYPE_STOCK_IN = 1;
    const MOVE_TYPE_STOCK_OUT = 2;
    const MOVE_TYPE_ADJUSTMENT = 3;

    /**
     * Stock In Operation
     * Adds inventory to a warehouse and updates both tables
     */
    public function stockIn(array $data): array
    {
        return DB::transaction(function () use ($data) {
            try {
                Log::info("🔵 [STOCK IN] Starting stock in operation", [
                    'data' => $data,
                    'timestamp' => now()->toDateTimeString()
                ]);
                
                // Validate required fields
                $this->validateStockInData($data);

                // Get or create warehouse product record
                $warehouseProduct = $this->getOrCreateWarehouseProduct(
                    $data['warehouse_id'],
                    $data['product_id']
                );
                
                Log::info("🔵 [STOCK IN] Warehouse product found/created", [
                    'warehouse_id' => $data['warehouse_id'],
                    'product_id' => $data['product_id'],
                    'current_balance' => $warehouseProduct->balance,
                    'warehouse_product_id' => $warehouseProduct->id
                ]);

                // Calculate new balance
                $newBalance = $warehouseProduct->balance + $data['quantity'];
                
                Log::info("🔵 [STOCK IN] Balance calculation", [
                    'current_balance' => $warehouseProduct->balance,
                    'quantity_to_add' => $data['quantity'],
                    'new_balance' => $newBalance
                ]);

                // Create inventory transaction record
                $inventoryData = [
                    'product_id' => $data['product_id'],
                    'warehouse_id' => $data['warehouse_id'],
                    'sale_order_id' => $data['sale_order_id'] ?? null,
                    'transfer_slip_id' => $data['transfer_slip_id'] ?? null,
                    'contact_id' => $data['contact_id'] ?? null,
                    'date_activity' => $data['date_activity'] ?? now(),
                    'move_type_id' => self::MOVE_TYPE_STOCK_IN,
                    'detail' => $data['detail'] ?? 'Stock In',
                    'quantity_move' => $data['quantity'],
                    'unit_name' => $data['unit_name'] ?? $this->getProductUnit($data['product_id']),
                    'remaining' => $newBalance,
                    'avr_buy_price' => $data['unit_price'] ?? 0,
                    'avr_sale_price' => $data['sale_price'] ?? 0,
                    'avr_remain_price' => $data['unit_price'] ?? 0,
                ];
                
                Log::info("🔵 [STOCK IN] Creating inventory record", [
                    'inventory_data' => $inventoryData
                ]);
                
                $inventory = Inventory::create($inventoryData);
                
                Log::info("🔵 [STOCK IN] Inventory record created", [
                    'inventory_id' => $inventory->id,
                    'inventory_created_at' => $inventory->date_activity
                ]);

                // Update warehouse product with new balance and recalculate averages
                Log::info("🔵 [STOCK IN] Updating warehouse product", [
                    'before_update' => [
                        'balance' => $warehouseProduct->balance,
                        'avr_buy_price' => $warehouseProduct->avr_buy_price,
                        'avr_sale_price' => $warehouseProduct->avr_sale_price,
                        'avr_remain_price' => $warehouseProduct->avr_remain_price
                    ]
                ]);
                
                $this->updateWarehouseProductForStockIn($warehouseProduct, $data);
                
                Log::info("🔵 [STOCK IN] Warehouse product updated", [
                    'after_update' => [
                        'balance' => $warehouseProduct->fresh()->balance,
                        'avr_buy_price' => $warehouseProduct->fresh()->avr_buy_price,
                        'avr_sale_price' => $warehouseProduct->fresh()->avr_sale_price,
                        'avr_remain_price' => $warehouseProduct->fresh()->avr_remain_price
                    ]
                ]);

                Log::info("🔵 [STOCK IN] Operation completed successfully", [
                    'warehouse_id' => $data['warehouse_id'],
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity'],
                    'new_balance' => $newBalance,
                    'inventory_id' => $inventory->id
                ]);

                return [
                    'success' => true,
                    'message' => 'Stock In completed successfully',
                    'inventory_id' => $inventory->id,
                    'new_balance' => $newBalance,
                    'warehouse_product' => $warehouseProduct->fresh()
                ];

            } catch (Exception $e) {
                Log::error("Stock In failed", [
                    'error' => $e->getMessage(),
                    'data' => $data
                ]);
                
                throw new Exception("Stock In failed: " . $e->getMessage());
            }
        });
    }

    /**
     * Stock Out Operation
     * Removes inventory from a warehouse and updates both tables
     */
    public function stockOut(array $data): array
    {
        return DB::transaction(function () use ($data) {
            try {
                Log::info("🔴 [STOCK OUT] Starting stock out operation", [
                    'data' => $data,
                    'timestamp' => now()->toDateTimeString()
                ]);
                
                // Validate required fields
                $this->validateStockOutData($data);

                // Get warehouse product record
                $warehouseProduct = WarehouseProduct::where('warehouse_id', $data['warehouse_id'])
                    ->where('product_id', $data['product_id'])
                    ->first();

                if (!$warehouseProduct) {
                    Log::error("🔴 [STOCK OUT] Product not found in warehouse", [
                        'warehouse_id' => $data['warehouse_id'],
                        'product_id' => $data['product_id']
                    ]);
                    throw new Exception("Product not found in warehouse");
                }
                
                Log::info("🔴 [STOCK OUT] Warehouse product found", [
                    'warehouse_id' => $data['warehouse_id'],
                    'product_id' => $data['product_id'],
                    'current_balance' => $warehouseProduct->balance,
                    'warehouse_product_id' => $warehouseProduct->id
                ]);

                // Check if sufficient stock available
                if ($warehouseProduct->balance < $data['quantity']) {
                    Log::error("🔴 [STOCK OUT] Insufficient stock", [
                        'available' => $warehouseProduct->balance,
                        'requested' => $data['quantity'],
                        'shortage' => $data['quantity'] - $warehouseProduct->balance
                    ]);
                    throw new Exception("Insufficient stock. Available: {$warehouseProduct->balance}, Requested: {$data['quantity']}");
                }

                // Calculate new balance
                $newBalance = $warehouseProduct->balance - $data['quantity'];
                
                Log::info("🔴 [STOCK OUT] Balance calculation", [
                    'current_balance' => $warehouseProduct->balance,
                    'quantity_to_remove' => $data['quantity'],
                    'new_balance' => $newBalance
                ]);

                // Create inventory transaction record
                $inventory = Inventory::create([
                    'product_id' => $data['product_id'],
                    'warehouse_id' => $data['warehouse_id'],
                    'sale_order_id' => $data['sale_order_id'] ?? null,
                    'transfer_slip_id' => $data['transfer_slip_id'] ?? null,
                    'contact_id' => $data['contact_id'] ?? null,
                    'date_activity' => $data['date_activity'] ?? now(),
                    'move_type_id' => self::MOVE_TYPE_STOCK_OUT,
                    'detail' => $data['detail'] ?? 'Stock Out',
                    'quantity_move' => -$data['quantity'], // Negative for stock out
                    'unit_name' => $data['unit_name'] ?? $this->getProductUnit($data['product_id']),
                    'remaining' => $newBalance,
                    'avr_buy_price' => $warehouseProduct->avr_buy_price,
                    'avr_sale_price' => $warehouseProduct->avr_sale_price,
                    'avr_remain_price' => $warehouseProduct->avr_remain_price,
                ]);

                // Update warehouse product balance and prices
                Log::info("🔴 [STOCK OUT] Updating warehouse product", [
                    'before_update' => [
                        'balance' => $warehouseProduct->balance,
                        'avr_buy_price' => $warehouseProduct->avr_buy_price,
                        'avr_sale_price' => $warehouseProduct->avr_sale_price,
                        'avr_remain_price' => $warehouseProduct->avr_remain_price
                    ]
                ]);
                
                $warehouseProduct->balance = $newBalance;
                
                // Update prices if provided (for stock out, we might want to update prices)
                if (isset($data['unit_price']) && $data['unit_price'] > 0) {
                    $warehouseProduct->avr_buy_price = $data['unit_price'];
                    Log::info("🔴 [STOCK OUT] Updated buy price", ['new_price' => $data['unit_price']]);
                }
                if (isset($data['sale_price']) && $data['sale_price'] > 0) {
                    $warehouseProduct->avr_sale_price = $data['sale_price'];
                    Log::info("🔴 [STOCK OUT] Updated sale price", ['new_price' => $data['sale_price']]);
                }
                if (isset($data['unit_price']) && $data['unit_price'] > 0) {
                    $warehouseProduct->avr_remain_price = $data['unit_price'];
                    Log::info("🔴 [STOCK OUT] Updated remain price", ['new_price' => $data['unit_price']]);
                }
                
                $warehouseProduct->save();
                
                Log::info("🔴 [STOCK OUT] Warehouse product updated", [
                    'after_update' => [
                        'balance' => $warehouseProduct->fresh()->balance,
                        'avr_buy_price' => $warehouseProduct->fresh()->avr_buy_price,
                        'avr_sale_price' => $warehouseProduct->fresh()->avr_sale_price,
                        'avr_remain_price' => $warehouseProduct->fresh()->avr_remain_price
                    ]
                ]);

                Log::info("🔴 [STOCK OUT] Operation completed successfully", [
                    'warehouse_id' => $data['warehouse_id'],
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity'],
                    'new_balance' => $newBalance,
                    'inventory_id' => $inventory->id
                ]);

                return [
                    'success' => true,
                    'message' => 'Stock Out completed successfully',
                    'inventory_id' => $inventory->id,
                    'new_balance' => $newBalance,
                    'warehouse_product' => $warehouseProduct->fresh()
                ];

            } catch (Exception $e) {
                Log::error("Stock Out failed", [
                    'error' => $e->getMessage(),
                    'data' => $data
                ]);
                
                throw new Exception("Stock Out failed: " . $e->getMessage());
            }
        });
    }

    /**
     * Stock Adjustment Operation
     * Adjusts inventory quantity and updates both tables
     */
    public function stockAdjustment(array $data): array
    {
        return DB::transaction(function () use ($data) {
            try {
                Log::info("🟡 [STOCK ADJUSTMENT] Starting stock adjustment operation", [
                    'data' => $data,
                    'timestamp' => now()->toDateTimeString()
                ]);
                
                // Validate required fields
                $this->validateAdjustmentData($data);

                // Get or create warehouse product record
                $warehouseProduct = $this->getOrCreateWarehouseProduct(
                    $data['warehouse_id'],
                    $data['product_id']
                );
                
                Log::info("🟡 [STOCK ADJUSTMENT] Warehouse product found/created", [
                    'warehouse_id' => $data['warehouse_id'],
                    'product_id' => $data['product_id'],
                    'current_balance' => $warehouseProduct->balance,
                    'warehouse_product_id' => $warehouseProduct->id
                ]);

                // Calculate quantity difference
                $quantityDifference = $data['new_quantity'] - $warehouseProduct->balance;
                $newBalance = $data['new_quantity'];
                
                Log::info("🟡 [STOCK ADJUSTMENT] Adjustment calculation", [
                    'current_balance' => $warehouseProduct->balance,
                    'new_quantity' => $data['new_quantity'],
                    'quantity_difference' => $quantityDifference,
                    'adjustment_type' => $quantityDifference > 0 ? 'INCREASE' : ($quantityDifference < 0 ? 'DECREASE' : 'NO_CHANGE')
                ]);

                // Create inventory transaction record
                $inventory = Inventory::create([
                    'product_id' => $data['product_id'],
                    'warehouse_id' => $data['warehouse_id'],
                    'sale_order_id' => $data['sale_order_id'] ?? null,
                    'transfer_slip_id' => $data['transfer_slip_id'] ?? null,
                    'contact_id' => $data['contact_id'] ?? null,
                    'date_activity' => $data['date_activity'] ?? now(),
                    'move_type_id' => self::MOVE_TYPE_ADJUSTMENT,
                    'detail' => $data['detail'] ?? 'Stock Adjustment',
                    'quantity_move' => $quantityDifference,
                    'unit_name' => $data['unit_name'] ?? $this->getProductUnit($data['product_id']),
                    'remaining' => $newBalance,
                    'avr_buy_price' => $warehouseProduct->avr_buy_price,
                    'avr_sale_price' => $warehouseProduct->avr_sale_price,
                    'avr_remain_price' => $warehouseProduct->avr_remain_price,
                ]);

                // Update warehouse product balance and prices
                Log::info("🟡 [STOCK ADJUSTMENT] Updating warehouse product", [
                    'before_update' => [
                        'balance' => $warehouseProduct->balance,
                        'avr_buy_price' => $warehouseProduct->avr_buy_price,
                        'avr_sale_price' => $warehouseProduct->avr_sale_price,
                        'avr_remain_price' => $warehouseProduct->avr_remain_price
                    ]
                ]);
                
                $warehouseProduct->balance = $newBalance;
                
                // Update prices if provided (for adjustment, we might want to update prices)
                if (isset($data['unit_price']) && $data['unit_price'] > 0) {
                    $warehouseProduct->avr_buy_price = $data['unit_price'];
                    Log::info("🟡 [STOCK ADJUSTMENT] Updated buy price", ['new_price' => $data['unit_price']]);
                }
                if (isset($data['sale_price']) && $data['sale_price'] > 0) {
                    $warehouseProduct->avr_sale_price = $data['sale_price'];
                    Log::info("🟡 [STOCK ADJUSTMENT] Updated sale price", ['new_price' => $data['sale_price']]);
                }
                if (isset($data['unit_price']) && $data['unit_price'] > 0) {
                    $warehouseProduct->avr_remain_price = $data['unit_price'];
                    Log::info("🟡 [STOCK ADJUSTMENT] Updated remain price", ['new_price' => $data['unit_price']]);
                }
                
                $warehouseProduct->save();
                
                Log::info("🟡 [STOCK ADJUSTMENT] Warehouse product updated", [
                    'after_update' => [
                        'balance' => $warehouseProduct->fresh()->balance,
                        'avr_buy_price' => $warehouseProduct->fresh()->avr_buy_price,
                        'avr_sale_price' => $warehouseProduct->fresh()->avr_sale_price,
                        'avr_remain_price' => $warehouseProduct->fresh()->avr_remain_price
                    ]
                ]);

                Log::info("🟡 [STOCK ADJUSTMENT] Operation completed successfully", [
                    'warehouse_id' => $data['warehouse_id'],
                    'product_id' => $data['product_id'],
                    'old_balance' => $warehouseProduct->balance - $quantityDifference,
                    'new_balance' => $newBalance,
                    'adjustment' => $quantityDifference,
                    'inventory_id' => $inventory->id
                ]);

                return [
                    'success' => true,
                    'message' => 'Stock Adjustment completed successfully',
                    'inventory_id' => $inventory->id,
                    'old_balance' => $warehouseProduct->balance - $quantityDifference,
                    'new_balance' => $newBalance,
                    'adjustment' => $quantityDifference,
                    'warehouse_product' => $warehouseProduct->fresh()
                ];

            } catch (Exception $e) {
                Log::error("Stock Adjustment failed", [
                    'error' => $e->getMessage(),
                    'data' => $data
                ]);
                
                throw new Exception("Stock Adjustment failed: " . $e->getMessage());
            }
        });
    }

    /**
     * Transfer Stock between warehouses
     */
    public function transferStock(array $data): array
    {
        return DB::transaction(function () use ($data) {
            try {
                Log::info("🔄 [TRANSFER] Starting stock transfer operation", [
                    'data' => $data,
                    'timestamp' => now()->toDateTimeString()
                ]);
                
                // Validate required fields
                $this->validateTransferData($data);

                // Stock out from source warehouse
                Log::info("🔄 [TRANSFER] Executing stock out from source warehouse", [
                    'from_warehouse_id' => $data['from_warehouse_id'],
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity']
                ]);
                
                $stockOutResult = $this->stockOut([
                    'warehouse_id' => $data['from_warehouse_id'],
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity'],
                    'detail' => "Transfer to Warehouse #{$data['to_warehouse_id']}",
                    'transfer_slip_id' => $data['transfer_slip_id'] ?? null,
                    'date_activity' => $data['date_activity'] ?? now(),
                ]);
                
                Log::info("🔄 [TRANSFER] Stock out completed", [
                    'stock_out_result' => $stockOutResult
                ]);

                // Stock in to destination warehouse
                Log::info("🔄 [TRANSFER] Executing stock in to destination warehouse", [
                    'to_warehouse_id' => $data['to_warehouse_id'],
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity']
                ]);
                
                $stockInResult = $this->stockIn([
                    'warehouse_id' => $data['to_warehouse_id'],
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity'],
                    'unit_price' => $data['unit_price'] ?? 0,
                    'sale_price' => $data['sale_price'] ?? 0,
                    'detail' => "Transfer from Warehouse #{$data['from_warehouse_id']}",
                    'transfer_slip_id' => $data['transfer_slip_id'] ?? null,
                    'date_activity' => $data['date_activity'] ?? now(),
                ]);
                
                Log::info("🔄 [TRANSFER] Stock in completed", [
                    'stock_in_result' => $stockInResult
                ]);

                Log::info("🔄 [TRANSFER] Transfer operation completed successfully", [
                    'from_warehouse' => $data['from_warehouse_id'],
                    'to_warehouse' => $data['to_warehouse_id'],
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity'],
                    'stock_out_inventory_id' => $stockOutResult['inventory_id'] ?? null,
                    'stock_in_inventory_id' => $stockInResult['inventory_id'] ?? null
                ]);

                return [
                    'success' => true,
                    'message' => 'Stock Transfer completed successfully',
                    'stock_out' => $stockOutResult,
                    'stock_in' => $stockInResult
                ];

            } catch (Exception $e) {
                Log::error("Stock Transfer failed", [
                    'error' => $e->getMessage(),
                    'data' => $data
                ]);
                
                throw new Exception("Stock Transfer failed: " . $e->getMessage());
            }
        });
    }

    /**
     * Get current stock balance for a product in a warehouse
     */
    public function getStockBalance(int $warehouseId, int $productId): int
    {
        $warehouseProduct = WarehouseProduct::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->first();

        return $warehouseProduct ? $warehouseProduct->balance : 0;
    }

    /**
     * Get stock movement history for a product in a warehouse
     */
    public function getStockHistory(int $warehouseId, int $productId, int $limit = 50)
    {
        return Inventory::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->with(['moveType', 'product'])
            ->orderBy('date_activity', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Validate stock balance integrity
     */
    public function validateStockIntegrity(int $warehouseId, int $productId): array
    {
        // Get current balance from warehouses_products
        $currentBalance = $this->getStockBalance($warehouseId, $productId);

        // Calculate balance from inventory transactions
        $calculatedBalance = Inventory::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->sum('quantity_move');

        $isValid = $currentBalance == $calculatedBalance;

        return [
            'is_valid' => $isValid,
            'current_balance' => $currentBalance,
            'calculated_balance' => $calculatedBalance,
            'difference' => $currentBalance - $calculatedBalance
        ];
    }

    /**
     * Reconcile stock balance if there's a discrepancy
     */
    public function reconcileStock(int $warehouseId, int $productId): array
    {
        $validation = $this->validateStockIntegrity($warehouseId, $productId);

        if (!$validation['is_valid']) {
            // Update warehouses_products with calculated balance
            $warehouseProduct = WarehouseProduct::where('warehouse_id', $warehouseId)
                ->where('product_id', $productId)
                ->first();

            if ($warehouseProduct) {
                $warehouseProduct->balance = $validation['calculated_balance'];
                $warehouseProduct->save();
            }

            Log::warning("Stock reconciled", [
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'old_balance' => $validation['current_balance'],
                'new_balance' => $validation['calculated_balance']
            ]);
        }

        return $validation;
    }

    /**
     * Private helper methods
     */

    private function getOrCreateWarehouseProduct(int $warehouseId, int $productId): WarehouseProduct
    {
        Log::info("🔧 [HELPER] Getting or creating warehouse product", [
            'warehouse_id' => $warehouseId,
            'product_id' => $productId
        ]);
        
        $warehouseProduct = WarehouseProduct::firstOrCreate(
            [
                'warehouse_id' => $warehouseId,
                'product_id' => $productId
            ],
            [
                'balance' => 0,
                'avr_buy_price' => 0,
                'avr_sale_price' => 0,
                'avr_remain_price' => 0
            ]
        );
        
        Log::info("🔧 [HELPER] Warehouse product retrieved/created", [
            'warehouse_product_id' => $warehouseProduct->id,
            'was_recently_created' => $warehouseProduct->wasRecentlyCreated,
            'current_balance' => $warehouseProduct->balance
        ]);
        
        return $warehouseProduct;
    }

    private function updateWarehouseProductForStockIn(WarehouseProduct $warehouseProduct, array $data): void
    {
        $oldBalance = $warehouseProduct->balance;
        $newQuantity = $data['quantity'];
        $unitPrice = $data['unit_price'] ?? 0;
        $salePrice = $data['sale_price'] ?? 0;

        Log::info("🔧 [HELPER] Updating warehouse product for stock in", [
            'warehouse_product_id' => $warehouseProduct->id,
            'old_balance' => $oldBalance,
            'new_quantity' => $newQuantity,
            'unit_price' => $unitPrice,
            'sale_price' => $salePrice,
            'old_avr_buy_price' => $warehouseProduct->avr_buy_price,
            'old_avr_sale_price' => $warehouseProduct->avr_sale_price,
            'old_avr_remain_price' => $warehouseProduct->avr_remain_price
        ]);

        // Update balance
        $warehouseProduct->balance += $newQuantity;

        // Calculate weighted average prices
        if ($warehouseProduct->balance > 0) {
            $totalQuantity = $warehouseProduct->balance;
            
            // Weighted average for buy price
            $oldBuyPrice = $warehouseProduct->avr_buy_price;
            $warehouseProduct->avr_buy_price = 
                (($warehouseProduct->avr_buy_price * $oldBalance) + ($unitPrice * $newQuantity)) / $totalQuantity;
            
            // Weighted average for sale price
            $oldSalePrice = $warehouseProduct->avr_sale_price;
            $warehouseProduct->avr_sale_price = 
                (($warehouseProduct->avr_sale_price * $oldBalance) + ($salePrice * $newQuantity)) / $totalQuantity;
            
            // Update remaining price (usually same as buy price)
            $warehouseProduct->avr_remain_price = $warehouseProduct->avr_buy_price;
            
            Log::info("🔧 [HELPER] Weighted average calculation", [
                'total_quantity' => $totalQuantity,
                'buy_price_calculation' => "({$oldBuyPrice} * {$oldBalance} + {$unitPrice} * {$newQuantity}) / {$totalQuantity} = {$warehouseProduct->avr_buy_price}",
                'sale_price_calculation' => "({$oldSalePrice} * {$oldBalance} + {$salePrice} * {$newQuantity}) / {$totalQuantity} = {$warehouseProduct->avr_sale_price}"
            ]);
        }

        $warehouseProduct->save();
        
        Log::info("🔧 [HELPER] Warehouse product updated for stock in", [
            'new_balance' => $warehouseProduct->balance,
            'new_avr_buy_price' => $warehouseProduct->avr_buy_price,
            'new_avr_sale_price' => $warehouseProduct->avr_sale_price,
            'new_avr_remain_price' => $warehouseProduct->avr_remain_price
        ]);
    }

    private function getProductUnit(int $productId): string
    {
        $product = Product::find($productId);
        return $product ? $product->unit_name : 'pcs';
    }

    private function validateStockInData(array $data): void
    {
        $required = ['warehouse_id', 'product_id', 'quantity'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Field '{$field}' is required for stock in");
            }
        }

        if ($data['quantity'] <= 0) {
            throw new Exception("Quantity must be greater than 0");
        }
    }

    private function validateStockOutData(array $data): void
    {
        $required = ['warehouse_id', 'product_id', 'quantity'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Field '{$field}' is required for stock out");
            }
        }

        if ($data['quantity'] <= 0) {
            throw new Exception("Quantity must be greater than 0");
        }
    }

    private function validateAdjustmentData(array $data): void
    {
        $required = ['warehouse_id', 'product_id', 'new_quantity'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new Exception("Field '{$field}' is required for adjustment");
            }
        }

        if ($data['new_quantity'] < 0) {
            throw new Exception("New quantity cannot be negative");
        }
    }

    private function validateTransferData(array $data): void
    {
        $required = ['from_warehouse_id', 'to_warehouse_id', 'product_id', 'quantity'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Field '{$field}' is required for transfer");
            }
        }

        if ($data['quantity'] <= 0) {
            throw new Exception("Quantity must be greater than 0");
        }

        if ($data['from_warehouse_id'] == $data['to_warehouse_id']) {
            throw new Exception("Source and destination warehouses cannot be the same");
        }
    }
}
