<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WithholdingSeeder extends Seeder
{
    public function run()
    {
        $withholdings = [
            ['name' => 'WH 3%', 'price_percent' => 3.00],
            ['name' => 'WH 5%', 'price_percent' => 5.00],
        ];

        DB::table('withholdings')->insert($withholdings);
    }
}