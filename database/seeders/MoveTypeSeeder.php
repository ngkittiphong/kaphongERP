<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoveTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Stock In',       'sign' => '+',  'color' => 'green'],
            ['name' => 'Stock Out',      'sign' => '-',  'color' => 'red'],
            ['name' => 'Adjustment',     'sign' => '~',  'color' => 'orange'],
        ];

        DB::table('move_types')->insert($types);
    }
}