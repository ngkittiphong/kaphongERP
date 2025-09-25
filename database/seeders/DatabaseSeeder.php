<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            UserTypesSeeder::class,
            UserStatusesSeeder::class,
            WarehouseStatusesSeeder::class,
            AdminUsersSeeder::class,
            ProductTypeSeeder::class,
            ProductStatusSeeder::class,
            ProductGroupSeeder::class,
            VatSeeder::class,
            WithholdingSeeder::class,
            MoveTypeSeeder::class,
            ProductSeeder::class,
            ProductSubUnitSeeder::class,
            CompanyBranchSeeder::class,
            WarehouseSeeder::class,
            WarehouseProductSeeder::class,
            InventorySeeder::class,
            TransferSlipStatusSeeder::class,
            TransferSlipSeeder::class,
            StockCheckerSeeder::class,
            CheckStockReportSeeder::class,
            CheckStockDetailSeeder::class,
        ]);
    }
}
