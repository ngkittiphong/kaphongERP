<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransferSlipStatus;

class TransferSlipStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            [
                'name' => 'Pending',
                'sign' => 'PENDING',
                'color' => 'warning',
            ],
            [
                'name' => 'Approved',
                'sign' => 'APPROVED',
                'color' => 'primary',
            ],
            [
                'name' => 'In Transit',
                'sign' => 'IN_TRANSIT',
                'color' => 'info',
            ],
            [
                'name' => 'Delivered',
                'sign' => 'DELIVERED',
                'color' => 'success',
            ],
            [
                'name' => 'Completed',
                'sign' => 'COMPLETED',
                'color' => 'success',
            ],
        ];

        foreach ($statuses as $status) {
            TransferSlipStatus::create($status);
        }

        $this->command->info('Created ' . count($statuses) . ' transfer slip statuses.');
    }
}
