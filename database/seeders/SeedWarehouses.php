<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Warehouse;

class SeedWarehouses extends Seeder
{
    public function run()
    {
        $this->command->info('🏗️  Starting Warehouse Seeder...');

        // Check if branches exist
        $branches = Branch::all();
        
        if ($branches->isEmpty()) {
            $this->command->error('❌ No branches found. Please run CompanyBranchSeeder first.');
            return;
        }

        $this->command->info("📋 Found " . count($branches) . " branches. Creating 2 warehouses for each...");

        $warehouseCount = 0;

        foreach ($branches as $branch) {
            $this->command->info("🏢 Processing Branch: " . $branch->name_th);

            // Create Main Warehouse
            $mainWarehouse = Warehouse::create([
                'branch_id' => $branch->id,
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
            ]);

            // Create Secondary Warehouse
            $secondaryWarehouse = Warehouse::create([
                'branch_id' => $branch->id,
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
            ]);

            $warehouseCount += 2;
            $this->command->info("  ✅ Created: " . $mainWarehouse->warehouse_code . " & " . $secondaryWarehouse->warehouse_code);
        }

        $this->command->info("🎉 Successfully created {$warehouseCount} warehouses!");
        
        // Display summary
        $this->command->table(
            ['Branch', 'Main Warehouse', 'Secondary Warehouse'],
            $branches->map(function ($branch) {
                $warehouses = $branch->warehouses()->orderBy('warehouse_code')->get();
                return [
                    $branch->name_th,
                    $warehouses->where('is_main_warehouse', true)->first()->warehouse_code ?? 'N/A',
                    $warehouses->where('is_main_warehouse', false)->first()->warehouse_code ?? 'N/A'
                ];
            })
        );
    }
}
