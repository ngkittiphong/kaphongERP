<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductGroup;
use App\Models\ProductStatus;
use App\Models\Vat;
use App\Models\Withholding;
use App\Models\WarehouseProduct;
use App\Livewire\Product\ProductStockCard;
use App\Services\InventoryService;


class ProductDetail extends Component
{
    public $product;
    public $productId;
    public $showAddEditProductForm = false;
    public $productTypes = [];
    public $productGroups = [];
    public $productStatuses = [];
    public $vats = [];
    public $withholdings = [];
    
    // Warehouse product data
    public $warehouseProducts = [];
    public $totalQuantity = 0;
    public $totalValue = 0;
    public $averageBuyPrice = 0;
    public $averageSalePrice = 0;

    // Form fields
    public $name;
    public $sku_number;
    public $serial_number;
    public $product_type_id;
    public $product_group_id;
    public $product_group_name;
    public $product_status_id;
    public $unit_name;
    public $buy_price;
    public $buy_vat_id;
    public $buy_withholding_id;
    public $buy_description;
    public $sale_price;
    public $sale_vat_id;
    public $sale_withholding_id;
    public $sale_description;
    public $minimum_quantity;
    public $maximum_quantity;
    public $product_cover_img;

    // Stock adjustment modal properties
    public $showStockModal = false;
    public $selectedWarehouseId = null;
    public $selectedWarehouseName = '';
    public $operationType = '';
    public $quantity = 0;
    public $unitName = '';
    public $unitPrice = 0;
    public $salePrice = 0;
    public $detail = '';
    public $currentStock = 0;

    protected $listeners = [
        'ProductSelected' => 'loadProduct',
        'showAddProductForm' => 'displayAddProductForm',
        'showEditProductForm' => 'displayEditProductForm',
        'refreshComponent' => '$refresh',
        'createProduct' => 'createProduct',
        'updateProduct' => 'updateProduct',
        'deleteProduct' => 'deleteProduct',
        'processStockOperationConfirmed' => 'processStockOperation'
    ];

    public function mount($productId = null)
    {
        \Log::info("ProductDetail Component Mounted");
        if ($productId) {
            $this->loadProduct($productId);
        }
        $this->productTypes = ProductType::all();
        $this->productGroups = ProductGroup::all();
        $this->productStatuses = ProductStatus::all();
        $this->vats = Vat::all();
        $this->withholdings = Withholding::all();
    }

    public function loadProduct($productId)
    {
        \Log::info("ProductDetail Component Mounted");
        $this->showAddEditProductForm = false;
        $this->productId = $productId;
        $this->product = Product::with([
            'type',
            'group',
            'status',
            'subUnits',
            'inventories',
            'warehouseProducts.warehouse.branch'
        ])->find($productId);
        
        // Load warehouse product data
        $this->loadWarehouseProductData();
        
        $this->dispatch('productSelected', product: $this->product);
    }
    
    /**
     * Load warehouse product data for the current product
     */
    public function loadWarehouseProductData()
    {
        if (!$this->product) {
            return;
        }
        
        // Get all warehouse products for this product
        $this->warehouseProducts = WarehouseProduct::with(['warehouse.branch'])
            ->where('product_id', $this->product->id)
            ->where('balance', '>', 0) // Only show warehouses with stock
            ->get();
        
        // Calculate totals
        $this->totalQuantity = $this->warehouseProducts->sum('balance');
        $this->totalValue = $this->warehouseProducts->sum(function ($wp) {
            return $wp->balance * $wp->avr_remain_price;
        });
        
        // Calculate weighted average prices
        if ($this->totalQuantity > 0) {
            $this->averageBuyPrice = $this->warehouseProducts->sum(function ($wp) {
                return $wp->balance * $wp->avr_buy_price;
            }) / $this->totalQuantity;
            
            $this->averageSalePrice = $this->warehouseProducts->sum(function ($wp) {
                return $wp->balance * $wp->avr_sale_price;
            }) / $this->totalQuantity;
        }
    }

    public function render()
    {
        // Force refresh warehouse data on every render
        if ($this->product) {
            $this->loadWarehouseProductData();
        }
        return view('livewire.product.product-detail');
    }

    public function displayAddProductForm()
    {
        \Log::info("Livewire Event Received: showAddProductForm");
        $this->showAddEditProductForm = true;
        $this->resetErrorBag();
        $this->reset([
            'name', 'sku_number', 'serial_number', 'product_type_id', 
            'product_group_id', 'product_group_name', 'product_status_id', 'unit_name', 
            'buy_price', 'buy_vat_id', 'buy_withholding_id', 'buy_description',
            'sale_price', 'sale_vat_id', 'sale_withholding_id', 'sale_description',
            'minimum_quantity', 'maximum_quantity', 'product_cover_img'
        ]);
        
        // Set default values
        $this->buy_price = 0.00;
        $this->sale_price = 0.00;
        
        $this->product = null;
        $this->dispatch('addProduct');
    }

    public function displayEditProductForm()
    {
        \Log::info("Livewire Event Received: showEditProductForm");
        $this->showAddEditProductForm = true;
        $this->resetErrorBag();
        \Log::info("Product ID: " . $this->productId);
        \Log::info("Product: " . json_encode($this->product));
        $this->name = $this->product->name;
        $this->sku_number = $this->product->sku_number;
        $this->serial_number = $this->product->serial_number;
        $this->product_type_id = $this->product->product_type_id;
        $this->product_group_id = $this->product->product_group_id;
        $this->product_status_id = $this->product->product_status_id;
        $this->unit_name = $this->product->unit_name;
        $this->buy_price = $this->product->buy_price;
        $this->buy_vat_id = $this->product->buy_vat_id;
        $this->buy_withholding_id = $this->product->buy_withholding_id;
        $this->buy_description = $this->product->buy_description;
        $this->sale_price = $this->product->sale_price;
        $this->sale_vat_id = $this->product->sale_vat_id;
        $this->sale_withholding_id = $this->product->sale_withholding_id;
        $this->sale_description = $this->product->sale_description;
        $this->minimum_quantity = $this->product->minimum_quantity;
        $this->maximum_quantity = $this->product->maximum_quantity;
        $this->product_cover_img = $this->product->product_cover_img;
        $this->dispatch('addProduct');
    }

    public function createProduct()
    {
        \Log::info("createProduct method called");
        try {
            // Create a new request instance with all form data
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'name' => $this->name,
                'sku_number' => $this->sku_number,
                'serial_number' => $this->serial_number,
                'product_type_id' => $this->product_type_id,
                'product_group_name' => $this->product_group_name,
                'product_status_id' => $this->product_status_id,
                'unit_name' => $this->unit_name,
                'buy_price' => $this->buy_price,
                'buy_vat_id' => $this->buy_vat_id,
                'buy_withholding_id' => $this->buy_withholding_id,
                'buy_description' => $this->buy_description,
                'sale_price' => $this->sale_price,
                'sale_vat_id' => $this->sale_vat_id,
                'sale_withholding_id' => $this->sale_withholding_id,
                'sale_description' => $this->sale_description,
                'minimum_quantity' => $this->minimum_quantity,
                'maximum_quantity' => $this->maximum_quantity,
                'product_cover_img' => $this->product_cover_img,
            ]);

            // Log the request data
            \Log::info("Request data: " . $request->product_group_name);

            // Call the ProductController's store method
            $controller = new \App\Http\Controllers\ProductController();
            $response = $controller->store($request);
            $responseData = json_decode($response->getContent());

            if ($responseData->success) {
                \Log::info("Product created successfully");
                $this->showAddProductForm = false;
                $this->dispatch('refreshComponent');
                $this->dispatch('showSuccessMessage', message: $responseData->message);
                return redirect()->route('menu_product');
            } else {
                \Log::info("Error creating product createProduct: " . $responseData->message);
                $this->dispatch('showErrorMessage', message: $responseData->message);
            }
        } catch (\Exception $e) {
            \Log::info("Error creating product Exception: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Error creating product: ' . $e->getMessage());
        }
    }

    public function updateProduct()
    {
        \Log::info("updateProduct method called");
        try {
            // Create a new request instance with all form data
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'name' => $this->name,
                'sku_number' => $this->sku_number,
                'serial_number' => $this->serial_number,
                'product_type_id' => $this->product_type_id,
                'product_group_id' => $this->product_group_id,
                'product_status_id' => $this->product_status_id,
                'unit_name' => $this->unit_name,
                'buy_price' => $this->buy_price,
                'buy_vat_id' => $this->buy_vat_id,
                'buy_withholding_id' => $this->buy_withholding_id,
                'buy_description' => $this->buy_description,
                'sale_price' => $this->sale_price,
                'sale_vat_id' => $this->sale_vat_id,
                'sale_withholding_id' => $this->sale_withholding_id,
                'sale_description' => $this->sale_description,
                'minimum_quantity' => $this->minimum_quantity,
                'maximum_quantity' => $this->maximum_quantity,
                'product_cover_img' => $this->product_cover_img,
            ]);

            // Call the ProductController's update method
            $controller = new \App\Http\Controllers\ProductController();
            $response = $controller->update($request, $this->product);
            $responseData = json_decode($response->getContent());

            if ($responseData->success) {
                \Log::info("Product updated successfully");
                $this->showAddEditProductForm = false;
                $this->dispatch('refreshComponent');
                $this->dispatch('showSuccessMessage', message: $responseData->message);
                return redirect()->route('menu_product');
            } else {
                \Log::info("Error updating product: " . $responseData->message);
                $this->dispatch('showErrorMessage', message: $responseData->message);
            }
        } catch (\Exception $e) {
            \Log::info("Error updating product: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Open stock adjustment modal
     */
    public function openStockModal($warehouseId, $warehouseName)
    {
        \Log::info("🚀 [STOCK MODAL] Opening modal for warehouse: {$warehouseId}, name: {$warehouseName}");
        
        $this->selectedWarehouseId = $warehouseId;
        $this->selectedWarehouseName = $warehouseName;
        $this->showStockModal = true;
        $this->resetStockModalFields();
        
        // Get current stock for the warehouse
        if ($warehouseId > 0) {
            $warehouseProduct = WarehouseProduct::where('warehouse_id', $warehouseId)
                ->where('product_id', $this->product->id)
                ->first();
            $this->currentStock = $warehouseProduct ? $warehouseProduct->balance : 0;
        } else {
            // For "Total All Warehouses", sum up all stock
            $this->currentStock = $this->totalQuantity;
        }
        
        $this->unitName = $this->product->unit_name ?? 'pcs';
        
        \Log::info("🚀 [STOCK MODAL] Modal state set - showModal: {$this->showStockModal}, operationType: '{$this->operationType}', currentStock: {$this->currentStock}");
        
        $this->dispatch('showStockModal');
    }

    /**
     * Reset stock modal fields
     */
    public function resetStockModalFields()
    {
        $this->operationType = '';
        $this->quantity = 0;
        $this->unitPrice = 0;
        $this->salePrice = 0;
        $this->detail = '';
        $this->resetErrorBag();
    }

    /**
     * Handle operation type change
     */
    public function updatedOperationType()
    {
        // This method will be called automatically when operationType changes
        \Log::info("🔄 [STOCK MODAL] Operation type changed to: " . $this->operationType);
        \Log::info("🔄 [STOCK MODAL] Show modal: " . ($this->showStockModal ? 'true' : 'false'));
        \Log::info("🔄 [STOCK MODAL] Selected warehouse ID: " . $this->selectedWarehouseId);
    }

    /**
     * Process stock operation
     */
    public function processStockOperation($confirm = false)
    {
        \Log::info("🚀 [PROCESS] Starting processStockOperation", [
            'operationType' => $this->operationType,
            'quantity' => $this->quantity,
            'selectedWarehouseId' => $this->selectedWarehouseId,
            'productId' => $this->product ? $this->product->id : 'null',
            'confirm' => $confirm
        ]);

        try {
            $this->validate([
                'operationType' => 'required|in:stock_in,stock_out,adjustment',
                'quantity' => 'required|numeric|min:0.01',
                'unitPrice' => 'nullable|numeric|min:0',
                'salePrice' => 'nullable|numeric|min:0',
                'detail' => 'nullable|string|max:255'
            ]);
            
            \Log::info("🚀 [PROCESS] Validation passed");
        } catch (\Exception $e) {
            \Log::error("🚀 [PROCESS] Validation failed: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Validation failed: ' . $e->getMessage());
            return;
        }

        \Log::info("🚀 [PROCESS] About to check confirm parameter", ['confirm' => $confirm]);

        if (!$confirm) {
            if (!$this->product) {
                \Log::warning("🚀 [CONFIRM] Product not loaded, skipping confirmation");
                return;
            }

            $resolvedCurrentStock = 0;

            if ($this->selectedWarehouseId > 0) {
                $warehouseProduct = WarehouseProduct::where('warehouse_id', $this->selectedWarehouseId)
                    ->where('product_id', $this->product->id)
                    ->first();
                $resolvedCurrentStock = $warehouseProduct ? (float) $warehouseProduct->balance : 0;
            } else {
                $resolvedCurrentStock = (float) $this->totalQuantity;
            }

            \Log::info("🚀 [CONFIRM] Resolved current stock for confirmation", [
                'selectedWarehouseId' => $this->selectedWarehouseId,
                'resolvedCurrentStock' => $resolvedCurrentStock
            ]);

            $newStock = $this->operationType === 'adjustment'
                ? (float) $this->quantity
                : ($this->operationType === 'stock_in'
                    ? $resolvedCurrentStock + (float) $this->quantity
                    : $resolvedCurrentStock - (float) $this->quantity);

            \Log::info("🚀 [CONFIRM] Dispatching confirmStockOperation event", [
                'operationType' => $this->operationType,
                'currentStock' => $resolvedCurrentStock,
                'newStock' => $newStock
            ]);

            $this->dispatch('confirmStockOperation',
                operationType: $this->operationType,
                currentStock: $resolvedCurrentStock,
                newStock: $newStock
            );

            return;
        }

        // Additional validation for stock out operations
        if ($this->operationType === 'stock_out' && $this->selectedWarehouseId > 0) {
            $warehouseProduct = WarehouseProduct::where('warehouse_id', $this->selectedWarehouseId)
                ->where('product_id', $this->product->id)
                ->first();
            
            if (!$warehouseProduct || $warehouseProduct->balance < $this->quantity) {
                $this->addError('quantity', 'Insufficient stock. Available: ' . ($warehouseProduct ? $warehouseProduct->balance : 0));
                return;
            }
        }

        try {
            $inventoryService = new InventoryService();
            
            // Handle "Total All Warehouses" case
            if ($this->selectedWarehouseId == 0) {
                $this->processAllWarehousesOperation($inventoryService);
                return;
            }
            
            $data = [
                'product_id' => $this->product->id,
                'warehouse_id' => $this->selectedWarehouseId,
                'quantity' => $this->quantity,
                'unit_name' => $this->unitName,
                'unit_price' => $this->unitPrice,
                'sale_price' => $this->salePrice,
                'detail' => $this->detail ?: ucfirst(str_replace('_', ' ', $this->operationType)),
                'date_activity' => now()
            ];

            $result = null;

            switch ($this->operationType) {
                case 'stock_in':
                    $result = $inventoryService->stockIn($data);
                    break;
                case 'stock_out':
                    $result = $inventoryService->stockOut($data);
                    break;
                case 'adjustment':
                    $data['new_quantity'] = $this->quantity;
                    unset($data['quantity']);
                    $result = $inventoryService->stockAdjustment($data);
                    break;
            }

            if ($result && $result['success']) {
                $this->handleSuccessfulOperation($result['message']);
            } else {
                $this->dispatch('showErrorMessage', message: 'Stock operation failed');
            }

        } catch (\Exception $e) {
            \Log::error("Stock operation failed: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Stock operation failed: ' . $e->getMessage());
        }
    }

    /**
     * Process operation for all warehouses
     */
    private function processAllWarehousesOperation($inventoryService)
    {
        $warehouses = WarehouseProduct::where('product_id', $this->product->id)
            ->where('balance', '>', 0)
            ->get();

        $successCount = 0;
        $totalWarehouses = $warehouses->count();

        foreach ($warehouses as $warehouseProduct) {
            try {
                $data = [
                    'product_id' => $this->product->id,
                    'warehouse_id' => $warehouseProduct->warehouse_id,
                    'quantity' => $this->quantity,
                    'unit_name' => $this->unitName,
                    'unit_price' => $this->unitPrice,
                    'sale_price' => $this->salePrice,
                    'detail' => $this->detail ?: ucfirst(str_replace('_', ' ', $this->operationType)) . ' (All Warehouses)',
                    'date_activity' => now()
                ];

                $result = null;

                switch ($this->operationType) {
                    case 'stock_in':
                        $result = $inventoryService->stockIn($data);
                        break;
                    case 'stock_out':
                        $result = $inventoryService->stockOut($data);
                        break;
                    case 'adjustment':
                        $data['new_quantity'] = $this->quantity;
                        unset($data['quantity']);
                        $result = $inventoryService->stockAdjustment($data);
                        break;
                }

                if ($result && $result['success']) {
                    $successCount++;
                }
            } catch (\Exception $e) {
                \Log::error("Stock operation failed for warehouse {$warehouseProduct->warehouse_id}: " . $e->getMessage());
            }
        }

        if ($successCount > 0) {
            $message = "Stock operation completed successfully for {$successCount} out of {$totalWarehouses} warehouses";
            $this->handleSuccessfulOperation($message);
        } else {
            $this->dispatch('showErrorMessage', message: 'Stock operation failed for all warehouses');
        }
    }

    /**
     * Handle successful operation
     */
    private function handleSuccessfulOperation($message)
    {
        // Close modal
        $this->showStockModal = false;
        $this->resetStockModalFields();
        
        // Refresh warehouse data
        $this->loadWarehouseProductData();
        
        // Hide modal using JavaScript first
        $this->dispatch('hideStockModal');
        
        // Refresh stock card component
        $this->dispatch('refreshStockCard');

        // Show success message after a short delay to ensure modal is closed
        $this->dispatch('showSuccessMessage', message: $message);
    }

    /**
     * Close stock modal
     */
    public function closeStockModal()
    {
        $this->showStockModal = false;
        $this->resetStockModalFields();
        $this->dispatch('hideStockModal');
    }

    /**
     * Test method to set operation type
     */
    public function setOperationType($type)
    {
        $this->operationType = $type;
        \Log::info("🧪 [TEST] Operation type set to: {$this->operationType}");
    }

    /**
     * Simple test method
     */
    public function testMethod()
    {
        \Log::info("🧪 [TEST] Test method called successfully!");
        $this->dispatch('showSuccessMessage', message: 'Test method called!');
    }

    /**
     * Even simpler test method - no events
     */
    public function simpleTest()
    {
        \Log::info("🧪 [SIMPLE] Simple test method called - no events!");
        // Just update a property to show it worked
        $this->detail = "Simple test worked at " . now()->format('H:i:s');
    }

    
} 
