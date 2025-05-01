<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductGroupSeeder extends Seeder
{
    public function run()
    {
        $groups = [
            ['name' => 'กระเป๋าสตางค์', 'sign' => 'A', 'color' => 'teal'],
            ['name' => 'กระเป๋าถือ', 'sign' => 'B', 'color' => 'orange'],
            ['name' => 'เครื่องแต่งกาย', 'sign' => 'C', 'color' => 'purple'],
        ];

        DB::table('product_groups')->insert($groups);
    }
}