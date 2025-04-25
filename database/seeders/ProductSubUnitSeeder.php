<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSubUnit;

class ProductSubUnitSeeder extends Seeder
{
    public function run()
    {
        $faker    = \Faker\Factory::create();
        $products = \App\Models\Product::all();
        
        foreach ($products as $product) {
            ProductSubUnit::create([
                'product_id'                => $product->id,
                'serial_number'             => strtoupper($faker->bothify('SS-##??')),
                'name'                      => $product->unit_name . ' sub',
                'buy_price'                 => $faker->randomFloat(2, 5, $product->buy_price),
                'sale_price'                => $faker->randomFloat(2, 20, $product->sale_price),
                'quantity_of_minimum_unit'  => $faker->numberBetween(1, 10),
            ]);
        }
    }
}