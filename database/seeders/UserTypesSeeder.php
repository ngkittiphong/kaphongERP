<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('user_types')->insert([
            ['name' => 'Admin', 'sign' => 'A', 'color' => 'red', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'User', 'sign' => 'U', 'color' => 'blue', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
