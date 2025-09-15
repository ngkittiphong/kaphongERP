<?php

namespace Database\Seeders;

use App\Models\CheckStockDetail;
use App\Models\CheckStockReport;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CheckStockDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users, products, and check stock reports for seeding
        $users = User::take(3)->get();
        $products = Product::take(10)->get();
        $reports = CheckStockReport::take(3)->get();

        if ($users->isEmpty() || $products->isEmpty() || $reports->isEmpty()) {
            $this->command->warn('No users, products, or check stock reports found. Please run the required seeders first.');
            return;
        }

        // Create sample check stock details for each report
        foreach ($reports as $report) {
            // Create 3-5 details per report
            $detailCount = rand(3, 5);
            $selectedProducts = $products->random($detailCount);
            
            foreach ($selectedProducts as $product) {
                CheckStockDetail::create([
                    'user_check_id' => $users->random()->id,
                    'product_id' => $product->id,
                    'check_stock_report_id' => $report->id,
                    'product_scan_num' => rand(1, 50),
                    'datetime_scan' => Carbon::now()->subHours(rand(1, 48)),
                ]);
            }
        }

        $this->command->info('CheckStockDetailSeeder completed successfully.');
    }
}
