<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Warehouse;

class WarehouseTestSeeder extends Seeder
{
    public function run()
    {
        // Clear existing warehouses
        Warehouse::truncate();

        // Get all branches
        $branches = Branch::all();

        if ($branches->isEmpty()) {
            $this->command->warn('No branches found. Please run CompanyBranchSeeder first.');
            return;
        }

        foreach ($branches as $branch) {
            // Create 2 warehouses for each branch
            $warehouses = [
                [
                    'warehouse_code' => $branch->branch_code . '-WH01',
                    'name_th' => 'คลังหลัก - ' . $branch->name_th,
                    'name_en' => 'Main Warehouse - ' . $branch->name_en,
                    'address_th' => $branch->address_th,
                    'address_en' => $branch->address_en,
                    'phone_number' => $branch->phone_number,
                    'email' => 'warehouse1@' . explode('@', $branch->email)[1],
                    'is_active' => true,
                    'is_main_warehouse' => true,
                    'description' => 'คลังหลักของสาขา ' . $branch->name_th,
                    'contact_name' => $branch->contact_name,
                    'contact_email' => 'warehouse1@' . explode('@', $branch->email)[1],
                    'contact_mobile' => $branch->contact_mobile,
                ],
                [
                    'warehouse_code' => $branch->branch_code . '-WH02',
                    'name_th' => 'คลังสำรอง - ' . $branch->name_th,
                    'name_en' => 'Secondary Warehouse - ' . $branch->name_en,
                    'address_th' => $branch->address_th . ' (อาคาร 2)',
                    'address_en' => $branch->address_en . ' (Building 2)',
                    'phone_number' => $branch->phone_number,
                    'email' => 'warehouse2@' . explode('@', $branch->email)[1],
                    'is_active' => true,
                    'is_main_warehouse' => false,
                    'description' => 'คลังสำรองของสาขา ' . $branch->name_th,
                    'contact_name' => $branch->contact_name,
                    'contact_email' => 'warehouse2@' . explode('@', $branch->email)[1],
                    'contact_mobile' => $branch->contact_mobile,
                ]
            ];

            // Create warehouses for this branch
            foreach ($warehouses as $warehouseData) {
                Warehouse::create([
                    'branch_id' => $branch->id,
                    ...$warehouseData
                ]);
            }
        }

        $this->command->info('✅ Created ' . (count($branches) * 2) . ' warehouses for ' . count($branches) . ' branches.');
        
        // Display created warehouses
        $this->command->table(
            ['ID', 'Code', 'Name (TH)', 'Branch', 'Status'],
            Warehouse::with('branch')->get()->map(function ($warehouse) {
                return [
                    $warehouse->id,
                    $warehouse->warehouse_code,
                    $warehouse->name_th,
                    $warehouse->branch->name_th ?? 'N/A',
                    $warehouse->is_active ? 'Active' : 'Inactive'
                ];
            })
        );
    }
}
