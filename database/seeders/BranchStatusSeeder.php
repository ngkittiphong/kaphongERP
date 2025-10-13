<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchStatusSeeder extends Seeder
{
    public function run()
    {
        // 1. ปิด AUTO_INCREMENT Constraint ชั่วคราว (Allow insert 0)
        DB::statement('SET @@auto_increment_increment = 1;');
        DB::statement('SET @@sql_mode = "NO_AUTO_VALUE_ON_ZERO";');
    
        // 2. Insert ID = 0 ถ้ายังไม่มี
        if (!DB::table('branch_statuses')->where('id', 0)->exists()) {
            DB::table('branch_statuses')->insert([
                'id'    => 0,
                'name'  => 'Delete',
                'sign'  => 'DEL',
                'color' => 'red',
            ]);
        }
    
        // 3. รีเซ็ต Auto Increment ให้เริ่มที่ 1
        DB::statement("ALTER TABLE branch_statuses AUTO_INCREMENT = 1;");
    
        // 4. Insert values อื่นๆ
        $statuses = [
            ['name' => 'Active', 'sign' => 'ACT', 'color' => 'green'],
            ['name' => 'Inactive', 'sign' => 'INA', 'color' => 'grey'],
        ];
    
        foreach ($statuses as $status) {
            if (!DB::table('branch_statuses')->where('name', $status['name'])->exists()) {
                DB::table('branch_statuses')->insert($status);
            }
        }
    }
}
