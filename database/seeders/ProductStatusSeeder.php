<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductStatusSeeder extends Seeder
{
    public function run()
    {
        // Check if Delete status already exists
        $deleteStatus = DB::table('product_statuses')->where('id', 0)->first();
        
        if (!$deleteStatus) {
            // Insert the Delete status with ID 0
            DB::table('product_statuses')->insert([
                'id' => 0,
                'name' => 'Delete',
                'sign' => 'DEL',
                'color' => 'red'
            ]);
        }
        
        // Check if other statuses exist and insert only if they don't
        $existingStatuses = DB::table('product_statuses')->pluck('name')->toArray();
        
        $statuses = [
            ['name' => 'Active',       'sign' => 'ACT', 'color' => 'green'],
            ['name' => 'Inactive',     'sign' => 'INA', 'color' => 'grey'],
            ['name' => 'Discontinued', 'sign' => 'DIS', 'color' => 'black'],
        ];
        
        foreach ($statuses as $status) {
            if (!in_array($status['name'], $existingStatuses)) {
                DB::table('product_statuses')->insert($status);
            }
        }
    }
}