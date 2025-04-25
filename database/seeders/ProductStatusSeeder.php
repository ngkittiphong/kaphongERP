<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['name' => 'Active',       'sign' => 'ACT', 'color' => 'green'],
            ['name' => 'Inactive',     'sign' => 'INA', 'color' => 'grey'],
            ['name' => 'Discontinued', 'sign' => 'DIS', 'color' => 'black'],
        ];

        DB::table('product_statuses')->insert($statuses);
    }
}