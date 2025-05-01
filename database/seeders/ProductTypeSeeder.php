<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Service', 'sign' => 'Serv', 'color' => 'blue'],
            ['name' => 'Inventory',   'sign' => 'Inven', 'color' => 'brown'],
            ['name' => 'Non Inventory',     'sign' => 'Non Inven',  'color' => 'red'],
        ];

        DB::table('product_types')->insert($types);
    }
}