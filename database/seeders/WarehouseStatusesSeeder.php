<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('warehouse_statuses')->insert([
            ['name' => 'Active', 'sign' => '✔', 'color' => 'green', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inactive', 'sign' => '✖', 'color' => 'gray', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
