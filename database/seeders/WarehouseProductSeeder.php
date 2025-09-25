<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\WarehouseProduct;
use App\Models\Branch;
use Faker\Factory as Faker;

class WarehouseProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get all warehouses and products
        $warehouses = Warehouse::all();
        $products = Product::all();

        if ($warehouses->isEmpty()) {
            $this->command->error('No warehouses found. Please run WarehouseSeeder first.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->error('No products found. Please run ProductSeeder first.');
            return;
        }

        $this->command->info('Creating warehouse-product relationships with inventory data...');
        
        $warehouseProductCount = 0;
        $totalInventoryValue = 0;

        foreach ($warehouses as $warehouse) {
            // Each warehouse gets 70-90% of all products (some products may not be in every warehouse)
            $productsForWarehouse = $products->random(rand(70, 90) / 100 * $products->count());
            
            foreach ($productsForWarehouse as $product) {
                // Generate realistic inventory data
                $balance = $this->generateRealisticBalance($product, $warehouse);
                
                if ($balance > 0) {
                    // Generate realistic pricing based on product's base prices
                    $avrBuyPrice = $this->generateRealisticBuyPrice($product);
                    $avrSalePrice = $this->generateRealisticSalePrice($product, $avrBuyPrice);
                    $avrRemainPrice = $avrBuyPrice; // Usually same as buy price
                    
                    WarehouseProduct::create([
                        'warehouse_id' => $warehouse->id,
                        'product_id' => $product->id,
                        'balance' => $balance,
                        'avr_buy_price' => $avrBuyPrice,
                        'avr_sale_price' => $avrSalePrice,
                        'avr_remain_price' => $avrRemainPrice,
                    ]);
                    
                    $warehouseProductCount++;
                    $totalInventoryValue += ($balance * $avrRemainPrice);
                }
            }
        }

        $this->command->info("✅ Created {$warehouseProductCount} warehouse-product relationships");
        $this->command->info("💰 Total inventory value: $" . number_format($totalInventoryValue, 2));
        
        // Display summary by warehouse
        $this->displayWarehouseSummary();
        
        // Display summary by product category
        $this->displayProductSummary();
        
        // Display low stock alerts
        $this->displayLowStockAlerts();
    }

    /**
     * Generate realistic balance based on product and warehouse characteristics
     */
    private function generateRealisticBalance(Product $product, Warehouse $warehouse): int
    {
        $faker = \Faker\Factory::create();
        
        // Main warehouses typically have higher stock levels
        $baseMultiplier = $warehouse->main_warehouse ? 1.5 : 1.0;
        
        // Generate balance based on product's min/max quantities
        $minQty = $product->minimum_quantity ?? 1;
        $maxQty = $product->maximum_quantity ?? 100;
        
        // Some products might be out of stock (20% chance)
        if ($faker->boolean(20)) {
            return 0;
        }
        
        // Generate balance with some randomness
        $baseBalance = rand($minQty, $maxQty);
        $balance = (int) ($baseBalance * $baseMultiplier * $faker->randomFloat(1, 0.5, 2.0));
        
        return max(0, $balance);
    }

    /**
     * Generate realistic buy price based on product's base buy price
     */
    private function generateRealisticBuyPrice(Product $product): float
    {
        $faker = \Faker\Factory::create();
        $basePrice = $product->buy_price ?? 0;
        
        if ($basePrice == 0) {
            // Generate random price if product doesn't have a base price
            return $faker->randomFloat(2, 10, 500);
        }
        
        // Add some variation to the base price (±10%)
        $variation = $faker->randomFloat(2, 0.9, 1.1);
        return round($basePrice * $variation, 2);
    }

    /**
     * Generate realistic sale price based on buy price
     */
    private function generateRealisticSalePrice(Product $product, float $buyPrice): float
    {
        $faker = \Faker\Factory::create();
        $baseSalePrice = $product->sale_price ?? 0;
        
        if ($baseSalePrice == 0) {
            // Generate sale price as 1.5-3x buy price
            $markup = $faker->randomFloat(2, 1.5, 3.0);
            return round($buyPrice * $markup, 2);
        }
        
        // Use product's base sale price with some variation
        $variation = $faker->randomFloat(2, 0.9, 1.1);
        return round($baseSalePrice * $variation, 2);
    }

    /**
     * Display summary by warehouse
     */
    private function displayWarehouseSummary()
    {
        $this->command->info("\n🏢 Warehouse Inventory Summary:");
        
        $warehouseStats = WarehouseProduct::with(['warehouse.branch'])
            ->selectRaw('warehouse_id, COUNT(*) as product_count, SUM(balance) as total_quantity, SUM(balance * avr_remain_price) as total_value')
            ->groupBy('warehouse_id')
            ->get();
            
        $this->command->table(
            ['Warehouse', 'Branch', 'Products', 'Total Qty', 'Total Value'],
            $warehouseStats->map(function ($stat) {
                return [
                    $stat->warehouse->name ?? 'N/A',
                    $stat->warehouse->branch->name_en ?? 'N/A',
                    $stat->product_count,
                    number_format($stat->total_quantity),
                    '$' . number_format($stat->total_value, 2)
                ];
            })
        );
    }

    /**
     * Display summary by product
     */
    private function displayProductSummary()
    {
        $this->command->info("\n📦 Top Products by Total Inventory Value:");
        
        $productStats = WarehouseProduct::with('product')
            ->selectRaw('product_id, SUM(balance) as total_quantity, SUM(balance * avr_remain_price) as total_value')
            ->groupBy('product_id')
            ->orderBy('total_value', 'desc')
            ->limit(10)
            ->get();
            
        $this->command->table(
            ['Product', 'Total Qty', 'Total Value'],
            $productStats->map(function ($stat) {
                return [
                    $stat->product->name ?? 'N/A',
                    number_format($stat->total_quantity),
                    '$' . number_format($stat->total_value, 2)
                ];
            })
        );
    }

    /**
     * Display low stock alerts
     */
    private function displayLowStockAlerts()
    {
        $this->command->info("\n⚠️ Low Stock Alerts:");
        
        $lowStockItems = WarehouseProduct::with(['warehouse', 'product'])
            ->whereColumn('balance', '<=', 'products.minimum_quantity')
            ->join('products', 'warehouses_products.product_id', '=', 'products.id')
            ->get();
            
        if ($lowStockItems->isEmpty()) {
            $this->command->info("No low stock items found.");
            return;
        }
        
        $this->command->table(
            ['Product', 'Warehouse', 'Current Qty', 'Min Qty', 'Status'],
            $lowStockItems->map(function ($item) {
                $status = $item->balance == 0 ? 'OUT OF STOCK' : 'LOW STOCK';
                return [
                    $item->product->name ?? 'N/A',
                    $item->warehouse->name ?? 'N/A',
                    $item->balance,
                    $item->product->minimum_quantity ?? 0,
                    $status
                ];
            })
        );
    }
}
