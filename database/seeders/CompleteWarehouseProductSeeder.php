<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\WarehouseProduct;
use App\Models\Branch;
use App\Models\User;
use App\Models\WarehouseStatus;
use App\Models\ProductType;
use App\Models\ProductGroup;
use App\Models\ProductStatus;
use App\Models\Vat;
use App\Models\Withholding;
use Faker\Factory as Faker;

class CompleteWarehouseProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $this->command->info('🚀 Starting Complete Warehouse-Product Seeding...');
        
        // Step 1: Ensure we have basic reference data
        $this->ensureReferenceData();
        
        // Step 2: Create warehouses if needed
        $this->ensureWarehouses();
        
        // Step 3: Create products if needed
        $this->ensureProducts();
        
        // Step 4: Create warehouse-product relationships
        $this->createWarehouseProductRelationships();
        
        $this->command->info('✅ Complete warehouse-product seeding finished!');
    }

    /**
     * Ensure we have all required reference data
     */
    private function ensureReferenceData()
    {
        $this->command->info('📋 Ensuring reference data...');
        
        // Check and create warehouse statuses if needed
        if (WarehouseStatus::count() == 0) {
            $this->command->warn('No warehouse statuses found. Creating basic statuses...');
            WarehouseStatus::create(['name' => 'Active', 'description' => 'Active warehouse']);
            WarehouseStatus::create(['name' => 'Inactive', 'description' => 'Inactive warehouse']);
        }
        
        // Check and create product types if needed
        if (ProductType::count() == 0) {
            $this->command->warn('No product types found. Creating basic types...');
            ProductType::create(['name' => 'Electronics', 'description' => 'Electronic products']);
            ProductType::create(['name' => 'Clothing', 'description' => 'Clothing items']);
            ProductType::create(['name' => 'Home & Garden', 'description' => 'Home and garden products']);
        }
        
        // Check and create product groups if needed
        if (ProductGroup::count() == 0) {
            $this->command->warn('No product groups found. Creating basic groups...');
            ProductGroup::create(['name' => 'Consumer Goods', 'description' => 'Consumer products']);
            ProductGroup::create(['name' => 'Industrial', 'description' => 'Industrial products']);
            ProductGroup::create(['name' => 'Office Supplies', 'description' => 'Office and stationery']);
        }
        
        // Check and create product statuses if needed
        if (ProductStatus::count() == 0) {
            $this->command->warn('No product statuses found. Creating basic statuses...');
            ProductStatus::create(['name' => 'Active', 'description' => 'Active product']);
            ProductStatus::create(['name' => 'Discontinued', 'description' => 'Discontinued product']);
        }
        
        // Check and create VAT rates if needed
        if (Vat::count() == 0) {
            $this->command->warn('No VAT rates found. Creating basic rates...');
            Vat::create(['name' => 'Standard VAT', 'rate' => 7.0, 'description' => 'Standard VAT rate']);
            Vat::create(['name' => 'Zero VAT', 'rate' => 0.0, 'description' => 'Zero VAT rate']);
        }
        
        // Check and create withholding rates if needed
        if (Withholding::count() == 0) {
            $this->command->warn('No withholding rates found. Creating basic rates...');
            Withholding::create(['name' => 'Standard Withholding', 'rate' => 3.0, 'description' => 'Standard withholding rate']);
            Withholding::create(['name' => 'No Withholding', 'rate' => 0.0, 'description' => 'No withholding']);
        }
    }

    /**
     * Ensure we have warehouses
     */
    private function ensureWarehouses()
    {
        $this->command->info('🏢 Ensuring warehouses...');
        
        if (Warehouse::count() == 0) {
            $this->command->warn('No warehouses found. Creating sample warehouses...');
            
            // Get or create a branch
            $branch = Branch::first();
            if (!$branch) {
                $this->command->warn('No branches found. Creating sample branch...');
                $branch = Branch::create([
                    'company_id' => 1,
                    'branch_code' => 'MAIN',
                    'name_th' => 'สาขาหลัก',
                    'name_en' => 'Main Branch',
                    'address_th' => '123 ถนนสุขุมวิท กรุงเทพฯ',
                    'address_en' => '123 Sukhumvit Road, Bangkok',
                    'phone_number' => '02-123-4567',
                    'email' => 'main@company.com',
                    'contact_name' => 'John Doe',
                    'contact_mobile' => '081-234-5678',
                ]);
            }
            
            // Get or create a user
            $user = User::first();
            if (!$user) {
                $this->command->warn('No users found. Creating sample user...');
                $user = User::create([
                    'name' => 'Admin User',
                    'email' => 'admin@company.com',
                    'password' => bcrypt('password'),
                    'user_type_id' => 1,
                    'user_status_id' => 1,
                ]);
            }
            
            // Get active warehouse status
            $activeStatus = WarehouseStatus::where('name', 'Active')->first();
            
            // Create sample warehouses
            $warehouseNames = [
                'Main Warehouse',
                'Secondary Warehouse',
                'Finished Goods Warehouse',
                'Raw Materials Warehouse',
                'Export Warehouse'
            ];
            
            foreach ($warehouseNames as $index => $name) {
                Warehouse::create([
                    'branch_id' => $branch->id,
                    'user_create_id' => $user->id,
                    'main_warehouse' => $index === 0,
                    'name' => $name,
                    'date_create' => now()->subDays(rand(1, 365)),
                    'warehouse_status_id' => $activeStatus->id,
                    'avr_remain_price' => rand(1000, 50000),
                ]);
            }
        }
        
        $this->command->info('✅ Warehouses ready: ' . Warehouse::count() . ' warehouses');
    }

    /**
     * Ensure we have products
     */
    private function ensureProducts()
    {
        $this->command->info('📦 Ensuring products...');
        
        if (Product::count() == 0) {
            $this->command->warn('No products found. Creating sample products...');
            
            $productTypes = ProductType::pluck('id')->toArray();
            $productGroups = ProductGroup::pluck('id')->toArray();
            $productStatuses = ProductStatus::pluck('id')->toArray();
            $vatRates = Vat::pluck('id')->toArray();
            $withholdingRates = Withholding::pluck('id')->toArray();
            
            $sampleProducts = [
                ['name' => 'Smartphone', 'buy_price' => 500, 'sale_price' => 750, 'unit' => 'pcs'],
                ['name' => 'Laptop', 'buy_price' => 1000, 'sale_price' => 1500, 'unit' => 'pcs'],
                ['name' => 'T-Shirt', 'buy_price' => 20, 'sale_price' => 35, 'unit' => 'pcs'],
                ['name' => 'Office Chair', 'buy_price' => 150, 'sale_price' => 250, 'unit' => 'pcs'],
                ['name' => 'Notebook', 'buy_price' => 5, 'sale_price' => 8, 'unit' => 'pcs'],
                ['name' => 'Coffee Mug', 'buy_price' => 8, 'sale_price' => 15, 'unit' => 'pcs'],
                ['name' => 'Desk Lamp', 'buy_price' => 45, 'sale_price' => 75, 'unit' => 'pcs'],
                ['name' => 'Wireless Mouse', 'buy_price' => 25, 'sale_price' => 40, 'unit' => 'pcs'],
                ['name' => 'Keyboard', 'buy_price' => 60, 'sale_price' => 100, 'unit' => 'pcs'],
                ['name' => 'Monitor', 'buy_price' => 300, 'sale_price' => 450, 'unit' => 'pcs'],
            ];
            
            foreach ($sampleProducts as $index => $productData) {
                Product::create([
                    'product_type_id' => $productTypes[array_rand($productTypes)],
                    'product_group_id' => $productGroups[array_rand($productGroups)],
                    'sku_number' => 'SKU-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'serial_number' => 'SN-' . strtoupper($faker->bothify('???-####')),
                    'name' => $productData['name'],
                    'product_cover_img' => null,
                    'unit_name' => $productData['unit'],
                    'buy_price' => $productData['buy_price'],
                    'buy_vat_id' => $vatRates[array_rand($vatRates)],
                    'buy_withholding_id' => $withholdingRates[array_rand($withholdingRates)],
                    'buy_description' => 'High-quality ' . $productData['name'] . ' for professional use.',
                    'sale_price' => $productData['sale_price'],
                    'sale_vat_id' => $vatRates[array_rand($vatRates)],
                    'sale_withholding_id' => $withholdingRates[array_rand($withholdingRates)],
                    'sale_description' => 'Premium ' . $productData['name'] . ' with excellent value.',
                    'minimum_quantity' => rand(1, 10),
                    'maximum_quantity' => rand(50, 500),
                    'product_status_id' => $productStatuses[array_rand($productStatuses)],
                    'date_create' => now()->subDays(rand(1, 365)),
                ]);
            }
        }
        
        $this->command->info('✅ Products ready: ' . Product::count() . ' products');
    }

    /**
     * Create warehouse-product relationships
     */
    private function createWarehouseProductRelationships()
    {
        $this->command->info('🔗 Creating warehouse-product relationships...');
        
        // Clear existing relationships
        WarehouseProduct::truncate();
        
        $warehouses = Warehouse::all();
        $products = Product::all();
        $faker = Faker::create();
        
        $warehouseProductCount = 0;
        $totalInventoryValue = 0;
        
        foreach ($warehouses as $warehouse) {
            // Each warehouse gets 60-80% of all products
            $productsForWarehouse = $products->random(rand(60, 80) / 100 * $products->count());
            
            foreach ($productsForWarehouse as $product) {
                // Generate realistic inventory data
                $balance = $this->generateRealisticBalance($product, $warehouse);
                
                if ($balance > 0) {
                    // Generate realistic pricing
                    $avrBuyPrice = $this->generateRealisticBuyPrice($product);
                    $avrSalePrice = $this->generateRealisticSalePrice($product, $avrBuyPrice);
                    $avrRemainPrice = $avrBuyPrice;
                    
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
        
        // Display final summary
        $this->displayFinalSummary();
    }

    /**
     * Generate realistic balance
     */
    private function generateRealisticBalance(Product $product, Warehouse $warehouse): int
    {
        $faker = Faker::create();
        
        // Main warehouses typically have higher stock levels
        $baseMultiplier = $warehouse->main_warehouse ? 1.5 : 1.0;
        
        // Generate balance based on product's min/max quantities
        $minQty = $product->minimum_quantity ?? 1;
        $maxQty = $product->maximum_quantity ?? 100;
        
        // Some products might be out of stock (15% chance)
        if ($faker->boolean(15)) {
            return 0;
        }
        
        // Generate balance with some randomness
        $baseBalance = rand($minQty, $maxQty);
        $balance = (int) ($baseBalance * $baseMultiplier * $faker->randomFloat(1, 0.5, 2.0));
        
        return max(0, $balance);
    }

    /**
     * Generate realistic buy price
     */
    private function generateRealisticBuyPrice(Product $product): float
    {
        $faker = Faker::create();
        $basePrice = $product->buy_price ?? 0;
        
        if ($basePrice == 0) {
            return $faker->randomFloat(2, 10, 500);
        }
        
        // Add some variation to the base price (±10%)
        $variation = $faker->randomFloat(2, 0.9, 1.1);
        return round($basePrice * $variation, 2);
    }

    /**
     * Generate realistic sale price
     */
    private function generateRealisticSalePrice(Product $product, float $buyPrice): float
    {
        $faker = Faker::create();
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
     * Display final summary
     */
    private function displayFinalSummary()
    {
        $this->command->info("\n📊 FINAL SUMMARY:");
        
        $this->command->table(
            ['Metric', 'Count'],
            [
                ['Warehouses', Warehouse::count()],
                ['Products', Product::count()],
                ['Warehouse-Product Relationships', WarehouseProduct::count()],
                ['Total Inventory Value', '$' . number_format(WarehouseProduct::sum(\DB::raw('balance * avr_remain_price')), 2)],
            ]
        );
        
        // Top warehouses by inventory value
        $this->command->info("\n🏆 Top Warehouses by Inventory Value:");
        $topWarehouses = WarehouseProduct::with('warehouse')
            ->selectRaw('warehouse_id, SUM(balance * avr_remain_price) as total_value')
            ->groupBy('warehouse_id')
            ->orderBy('total_value', 'desc')
            ->limit(5)
            ->get();
            
        $this->command->table(
            ['Warehouse', 'Total Value'],
            $topWarehouses->map(function ($item) {
                return [
                    $item->warehouse->name ?? 'N/A',
                    '$' . number_format($item->total_value, 2)
                ];
            })
        );
    }
}
