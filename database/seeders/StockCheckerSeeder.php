<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockChecker;
use App\Models\StockCheckerDetail;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\User;

class StockCheckerSeeder extends Seeder
{
    public function run()
    {
        // Get warehouses
        $warehouses = Warehouse::all();
        if ($warehouses->isEmpty()) {
            $this->command->error('No warehouses found. Please run WarehouseSeeder first.');
            return;
        }

        // Get products
        $products = Product::all();
        if ($products->isEmpty()) {
            $this->command->error('No products found. Please run ProductSeeder first.');
            return;
        }

        // Get users
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->error('No users found. Please run AdminUsersSeeder first.');
            return;
        }

        $stockCheckersCreated = 0;

        // Create stock checkers for each warehouse
        foreach ($warehouses as $warehouse) {
            // Create 2-3 stock checkers per warehouse
            $checkerCount = rand(2, 3);
            
            for ($i = 0; $i < $checkerCount; $i++) {
                $user = $users->random();
                
                $stockChecker = StockChecker::create([
                    'checker_number' => StockChecker::generateCheckerNumber(),
                    'user_check_id' => $user->id,
                    'warehouse_id' => $warehouse->id,
                    'date_create' => now()->subDays(rand(1, 30)),
                ]);

                // Create stock checker details for random products
                $selectedProducts = $products->random(rand(3, 8));
                
                foreach ($selectedProducts as $product) {
                    // Simulate stock counting with some discrepancies
                    $remainStock = rand(0, 100);
                    $countStock = $remainStock + rand(-5, 5); // Allow for some counting errors
                    
                    StockCheckerDetail::create([
                        'stock_checker_id' => $stockChecker->id,
                        'product_id' => $product->id,
                        'date_check' => $stockChecker->date_create->addMinutes(rand(0, 60)),
                        'count_stock' => max(0, $countStock), // Ensure non-negative
                        'remain_stock' => max(0, $remainStock), // Ensure non-negative
                    ]);
                }

                $stockCheckersCreated++;
            }
        }

        $this->command->info("Created {$stockCheckersCreated} stock checkers with details.");
    }
}
