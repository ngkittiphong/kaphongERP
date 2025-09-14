<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        // Get all branches
        $branches = Branch::all();

        $warehouseTypes = [
            ['th' => 'คลังหลัก', 'en' => 'Main Warehouse'],
            ['th' => 'คลังสำรอง', 'en' => 'Secondary Warehouse'],
            ['th' => 'คลังสินค้าสำเร็จรูป', 'en' => 'Finished Goods Warehouse'],
            ['th' => 'คลังวัตถุดิบ', 'en' => 'Raw Materials Warehouse'],
            ['th' => 'คลังส่งออก', 'en' => 'Export Warehouse'],
        ];

        foreach ($branches as $index => $branch) {
            // Create 2 warehouses for each branch
            $warehouseType1 = $warehouseTypes[0]; // Main Warehouse
            $warehouseType2 = $warehouseTypes[1]; // Secondary Warehouse
            
            $warehouses = [
                [
                    'warehouse_code' => $branch->branch_code . '-WH01',
                    'name_th' => $warehouseType1['th'] . ' - ' . $branch->name_th,
                    'name_en' => $warehouseType1['en'] . ' - ' . $branch->name_en,
                    'address_th' => $branch->address_th,
                    'address_en' => $branch->address_en,
                    'phone_number' => $branch->phone_number,
                    'email' => 'warehouse1@' . explode('@', $branch->email)[1],
                    'is_active' => true,
                    'is_main_warehouse' => true,
                    'description' => $warehouseType1['th'] . 'ของสาขา ' . $branch->name_th . ' สำหรับเก็บสินค้าทั่วไป',
                    'contact_name' => $branch->contact_name,
                    'contact_email' => 'warehouse1@' . explode('@', $branch->email)[1],
                    'contact_mobile' => $branch->contact_mobile,
                ],
                [
                    'warehouse_code' => $branch->branch_code . '-WH02',
                    'name_th' => $warehouseType2['th'] . ' - ' . $branch->name_th,
                    'name_en' => $warehouseType2['en'] . ' - ' . $branch->name_en,
                    'address_th' => $branch->address_th . ' (อาคาร 2)',
                    'address_en' => $branch->address_en . ' (Building 2)',
                    'phone_number' => $branch->phone_number,
                    'email' => 'warehouse2@' . explode('@', $branch->email)[1],
                    'is_active' => true,
                    'is_main_warehouse' => false,
                    'description' => $warehouseType2['th'] . 'ของสาขา ' . $branch->name_th . ' สำหรับเก็บสินค้าสำรอง',
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

        $this->command->info('Created ' . (count($branches) * 2) . ' warehouses for ' . count($branches) . ' branches.');
    }
}
