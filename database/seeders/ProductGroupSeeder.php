<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductGroupSeeder extends Seeder
{
    public function run()
    {
        $groups = [
            ['name' => 'Group A', 'sign' => 'A', 'color' => 'teal'],
            ['name' => 'Group B', 'sign' => 'B', 'color' => 'orange'],
            ['name' => 'Group C', 'sign' => 'C', 'color' => 'purple'],
        ];

        DB::table('product_groups')->insert($groups);
    }
}