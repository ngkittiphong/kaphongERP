<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        
        for ($i = 1; $i <= 10; $i++) {
            // Insert Admin User
            $userId = DB::table('users')->insertGetId([
                'username' => 'admin' . $i,
                'email' => 'admin' . $i . '@example.com',
                'password' => Hash::make('password'),
                'user_type_id' => DB::table('user_types')->where('name', 'Admin')->first()->id, // Admin Type
                'user_status_id' => DB::table('user_statuses')->where('name', 'Active')->first()->id, // Active Status
                'request_change_pass' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert User Profile
            DB::table('user_profiles')->insert([
                'user_id' => $userId,
                'profile_no' => strtoupper($faker->bothify('PRF###')),
                'avatar' => null, // Default no avatar
                'nickname' => $faker->firstName,
                'card_id_no' => $faker->numerify('#############'),
                'prefix_th' => 'นาย',
                'fullname_th' => $faker->name,
                'prefix_en' => 'Mr.',
                'fullname_en' => $faker->name,
                'birth_date' => $faker->dateTimeBetween('-40 years', '-20 years'),
                'description' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
