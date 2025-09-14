<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\WarehouseStatus;
use App\Models\User;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        // Get all branches
        $branches = Branch::all();
        
        // Get Active status ID
        $activeStatus = WarehouseStatus::where('name', 'Active')->first();
        if (!$activeStatus) {
            $this->command->error('Active warehouse status not found. Please run WarehouseStatusesSeeder first.');
            return;
        }

        // Get first user as creator
        $user = User::first();
        if (!$user) {
            $this->command->error('No users found. Please run AdminUsersSeeder first.');
            return;
        }

        $warehouseNames = [
            'Main Warehouse',
            'Secondary Warehouse',
            'Finished Goods Warehouse',
            'Raw Materials Warehouse',
            'Export Warehouse',
        ];

        foreach ($branches as $index => $branch) {
            // Create 2 warehouses for each branch
            $warehouses = [
                [
                    'name' => $warehouseNames[0] . ' - ' . $branch->name_en,
                    'main_warehouse' => true,
                    'warehouse_status_id' => $activeStatus->id,
                    'avr_remain_price' => 0.00,
                ],
                [
                    'name' => $warehouseNames[1] . ' - ' . $branch->name_en,
                    'main_warehouse' => false,
                    'warehouse_status_id' => $activeStatus->id,
                    'avr_remain_price' => 0.00,
                ]
            ];

            // Create warehouses for this branch
            foreach ($warehouses as $warehouseData) {
                Warehouse::create([
                    'branch_id' => $branch->id,
                    'user_create_id' => $user->id,
                    'date_create' => now(),
                    ...$warehouseData
                ]);
            }
        }

        $this->command->info('Created ' . (count($branches) * 2) . ' warehouses for ' . count($branches) . ' branches.');
    }
}
