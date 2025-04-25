<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Electronics', 'sign' => 'ELEC', 'color' => 'blue'],
            ['name' => 'Furniture',   'sign' => 'FURN', 'color' => 'brown'],
            ['name' => 'Apparel',     'sign' => 'APP',  'color' => 'red'],
        ];

        DB::table('product_types')->insert($types);
    }
}