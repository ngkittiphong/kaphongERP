<?php

namespace Database\Seeders;

use App\Models\CheckStockReport;
use App\Models\StockChecker;
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

        $this->command->info('Starting CheckStockReportSeeder with new CS pattern...');
        $createdReports = [];
        $createdCheckers = [];

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

        foreach ($reports as $index => $reportData) {
            $report = CheckStockReport::create($reportData);
            $createdReports[] = [
                'id' => $report->id,
                'warehouse' => $warehouses->where('id', $reportData['warehouse_id'])->first()->name,
                'user' => $users->where('id', $reportData['user_create_id'])->first()->username,
                'datetime_create' => $reportData['datetime_create']->format('Y-m-d H:i'),
                'closed' => $reportData['closed'] ? 'Yes' : 'No'
            ];
        }

        // Create StockChecker instances with new CS pattern
        foreach ($warehouses as $warehouse) {
            $user = $users->random();
            $checkerNumber = StockChecker::generateCheckerNumber();
            
            $stockChecker = StockChecker::create([
                'checker_number' => $checkerNumber,
                'user_check_id' => $user->id,
                'warehouse_id' => $warehouse->id,
                'date_create' => Carbon::now()->subDays(rand(1, 10)),
            ]);

            $createdCheckers[] = [
                'id' => $stockChecker->id,
                'checker_number' => $checkerNumber,
                'warehouse' => $warehouse->name,
                'user' => $user->username,
                'date_create' => $stockChecker->date_create->format('Y-m-d')
            ];
        }

        $this->command->info('CheckStockReportSeeder completed successfully.');
        
        // Display created check stock reports
        $this->command->info('Created Check Stock Reports:');
        foreach ($createdReports as $report) {
            $this->command->line("  - Report #{$report['id']} - {$report['warehouse']} ({$report['user']}) - {$report['datetime_create']} [Closed: {$report['closed']}]");
        }
        
        // Display created stock checkers
        $this->command->info('Generated Stock Checker Numbers:');
        foreach ($createdCheckers as $checker) {
            $this->command->line("  - {$checker['checker_number']} - {$checker['warehouse']} ({$checker['user']}) - {$checker['date_create']}");
        }
    }
}
