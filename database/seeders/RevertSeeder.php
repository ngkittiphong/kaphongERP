<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevertSeeder extends Seeder
{
    public function run()
    {
        // Remove only specific seeded data
        DB::table('users')->where('email', 'like', 'admin%@example.com')->delete();
        //DB::table('user_profiles')->delete();
        //DB::table('user_types')->delete();
        //DB::table('user_statuses')->delete();
    }
}
