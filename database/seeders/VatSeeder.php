<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VatSeeder extends Seeder
{
    public function run()
    {
        $vats = [
            ['name' => 'VAT 0%',  'price_percent' => 0.00],
            ['name' => 'VAT 7%',  'price_percent' => 7.00],
            ['name' => 'VAT 10%', 'price_percent' => 10.00],
        ];

        DB::table('vats')->insert($vats);
    }
}