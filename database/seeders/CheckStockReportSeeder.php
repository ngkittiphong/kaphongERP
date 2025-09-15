<?php

namespace Database\Seeders;

use App\Models\CheckStockReport;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CheckStockReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and warehouses for seeding
        $users = User::take(3)->get();
        $warehouses = Warehouse::take(2)->get();

        if ($users->isEmpty() || $warehouses->isEmpty()) {
            $this->command->warn('No users or warehouses found. Please run UserSeeder and WarehouseSeeder first.');
            return;
        }

        // Create some sample check stock reports
        $reports = [
            [
                'user_create_id' => $users->first()->id,
                'warehouse_id' => $warehouses->first()->id,
                'datetime_create' => Carbon::now()->subDays(5),
                'closed' => true,
            ],
            [
                'user_create_id' => $users->skip(1)->first()->id,
                'warehouse_id' => $warehouses->first()->id,
                'datetime_create' => Carbon::now()->subDays(3),
                'closed' => false,
            ],
            [
                'user_create_id' => $users->last()->id,
                'warehouse_id' => $warehouses->last()->id,
                'datetime_create' => Carbon::now()->subDays(1),
                'closed' => false,
            ],
        ];

        foreach ($reports as $reportData) {
            CheckStockReport::create($reportData);
        }

        $this->command->info('CheckStockReportSeeder completed successfully.');
    }
}
