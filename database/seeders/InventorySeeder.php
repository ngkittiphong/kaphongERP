<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use Faker\Factory as Faker;

class InventorySeeder extends Seeder
{
    public function run()
    {
        $faker       = Faker::create();
        $moveTypes   = \App\Models\MoveType::pluck('id')->all();
        $products    = \App\Models\Product::all();

        foreach ($products as $product) {
            Inventory::create([
                'product_id'      => $product->id,
                'warehouse_id'    => $faker->numberBetween(1, 3),
                'sale_order_id'   => null,
                'transfer_slip_id'=> null,
                'contact_id'      => null,
                'date_activity'   => now(),
                'move_type_id'    => $faker->randomElement($moveTypes),
                'detail'          => $faker->sentence(4),
                'quantity_move'   => $qty = $faker->numberBetween(1, 50),
                'unit_name'       => $product->unit_name,
                'remaining'       => $qty + $faker->numberBetween(0, 20),
                'avr_buy_price'   => $product->buy_price,
                'avr_sale_price'  => $product->sale_price,
                'avr_remain_price'=> $product->sale_price * ($qty / ($qty + 1)),
            ]);
        }
    }
}