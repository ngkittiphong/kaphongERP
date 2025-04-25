<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductGroup;
use App\Models\ProductStatus;
use App\Models\Vat;
use App\Models\Withholding;
use Faker\Factory as Faker;
use Bezhanov\Faker\Provider\Commerce;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $faker->addProvider(new Commerce($faker));

        $typeIds     = \App\Models\ProductType::pluck('id')->all();
        $groupIds    = \App\Models\ProductGroup::pluck('id')->all();
        $statusIds   = \App\Models\ProductStatus::pluck('id')->all();
        $vatIds      = \App\Models\Vat::pluck('id')->all();
        $withholdIds = \App\Models\Withholding::pluck('id')->all();

        foreach (range(1, 10) as $i) {
            Product::create([
                'product_type_id'     => $faker->randomElement($typeIds),
                'product_group_id'    => $faker->randomElement($groupIds),
                'sku_number'          => strtoupper($faker->bothify('SKU-##??')),
                'serial_number'       => strtoupper($faker->bothify('SN-???-####')),
                'name'                => $faker->productName(),
                'product_cover_img'   => null,
                'unit_name'           => $faker->randomElement(['pcs','box','set']),
                'buy_price'           => $faker->randomFloat(2, 10, 200),
                'buy_vat_id'          => $faker->randomElement($vatIds),
                'buy_withholding_id'  => $faker->randomElement($withholdIds),
                'buy_description'     => $faker->sentence(),
                'sale_price'          => $faker->randomFloat(2, 50, 400),
                'sale_vat_id'         => $faker->randomElement($vatIds),
                'sale_withholding_id' => $faker->randomElement($withholdIds),
                'sale_description'    => $faker->sentence(),
                'minimum_quantity'    => $faker->numberBetween(1, 5),
                'maximum_quantity'    => $faker->numberBetween(10, 100),
                'product_status_id'   => $faker->randomElement($statusIds),
                'date_create'         => now(),
            ]);
        }
    }
}