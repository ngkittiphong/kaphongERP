<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransferSlip;
use App\Models\TransferSlipDetail;
use App\Models\TransferSlipStatus;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\User;
use App\Models\MoveType;

class TransferSlipSeeder extends Seeder
{
    public function run()
    {
        // Get required data
        $warehouses = Warehouse::all();
        if ($warehouses->isEmpty()) {
            $this->command->error('No warehouses found. Please run WarehouseSeeder first.');
            return;
        }

        $products = Product::all();
        if ($products->isEmpty()) {
            $this->command->error('No products found. Please run ProductSeeder first.');
            return;
        }

        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->error('No users found. Please run AdminUsersSeeder first.');
            return;
        }

        $statuses = TransferSlipStatus::all();
        if ($statuses->isEmpty()) {
            $this->command->error('No transfer slip statuses found. Please run TransferSlipStatusSeeder first.');
            return;
        }

        $moveTypes = MoveType::all();
        if ($moveTypes->isEmpty()) {
            $this->command->error('No move types found. Please run MoveTypeSeeder first.');
            return;
        }

        $transferSlipsCreated = 0;

        // Create transfer slips
        for ($i = 0; $i < 20; $i++) {
            $originWarehouse = $warehouses->random();
            $destinationWarehouse = $warehouses->where('id', '!=', $originWarehouse->id)->random();
            $requestUser = $users->random();
            $receiveUser = $users->random();
            $status = $statuses->random();

            $transferSlip = TransferSlip::create([
                'user_request_id' => $requestUser->id,
                'user_receive_id' => $receiveUser->id,
                'transfer_slip_number' => TransferSlip::generateTransferSlipNumber(),
                'company_name' => 'Company ' . ($i + 1),
                'company_address' => 'Address ' . ($i + 1) . ', City, Country',
                'tax_id' => '1234567890' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'tel' => '0' . rand(100000000, 999999999),
                'date_request' => now()->subDays(rand(1, 30)),
                'user_request_name' => $requestUser->name ?? 'User ' . $requestUser->id,
                'deliver_name' => 'Delivery Person ' . ($i + 1),
                'date_receive' => $status->name === 'Delivered' ? now()->subDays(rand(1, 15)) : null,
                'user_receive_name' => $status->name === 'Delivered' ? ($receiveUser->name ?? 'User ' . $receiveUser->id) : null,
                'warehouse_origin_id' => $originWarehouse->id,
                'warehouse_origin_name' => $originWarehouse->name,
                'warehouse_destination_id' => $destinationWarehouse->id,
                'warehouse_destination_name' => $destinationWarehouse->name,
                'total_quantity' => 0, // Will be calculated from details
                'transfer_slip_status_id' => $status->id,
                'description' => 'Transfer description ' . ($i + 1),
                'note' => 'Transfer note ' . ($i + 1),
            ]);

            // Create transfer slip details
            $selectedProducts = $products->random(rand(2, 6));
            $totalQuantity = 0;

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 50);
                $costPerUnit = rand(10, 1000) / 100; // Random cost between 0.10 and 10.00
                $costTotal = $quantity * $costPerUnit;

                TransferSlipDetail::create([
                    'transfer_slip_id' => $transferSlip->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_description' => $product->description ?? 'Product description',
                    'quantity' => $quantity,
                    'unit_name' => $product->unit_name ?? 'pcs',
                    'cost_per_unit' => $costPerUnit,
                    'cost_total' => $costTotal,
                ]);

                $totalQuantity += $quantity;

                // Create inventory entries for this transfer
                $moveType = $moveTypes->random();
                
                // Outbound inventory entry (from origin warehouse)
                \App\Models\Inventory::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $originWarehouse->id,
                    'transfer_slip_id' => $transferSlip->id,
                    'date_activity' => $transferSlip->date_request,
                    'move_type_id' => $moveType->id,
                    'detail' => 'Transfer out - ' . $transferSlip->transfer_slip_number,
                    'quantity_move' => -$quantity, // Negative for outbound
                    'unit_name' => $product->unit_name ?? 'pcs',
                    'remaining' => rand(0, 100), // Random remaining stock
                    'avr_buy_price' => $costPerUnit,
                    'avr_sale_price' => $costPerUnit * 1.2, // 20% markup
                    'avr_remain_price' => $costPerUnit,
                ]);

                // Inbound inventory entry (to destination warehouse)
                \App\Models\Inventory::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $destinationWarehouse->id,
                    'transfer_slip_id' => $transferSlip->id,
                    'date_activity' => $transferSlip->date_receive ?? $transferSlip->date_request->addDays(rand(1, 5)),
                    'move_type_id' => $moveType->id,
                    'detail' => 'Transfer in - ' . $transferSlip->transfer_slip_number,
                    'quantity_move' => $quantity, // Positive for inbound
                    'unit_name' => $product->unit_name ?? 'pcs',
                    'remaining' => rand(0, 100), // Random remaining stock
                    'avr_buy_price' => $costPerUnit,
                    'avr_sale_price' => $costPerUnit * 1.2, // 20% markup
                    'avr_remain_price' => $costPerUnit,
                ]);
            }

            // Update total quantity
            $transferSlip->update(['total_quantity' => $totalQuantity]);

            $transferSlipsCreated++;
        }

        $this->command->info("Created {$transferSlipsCreated} transfer slips with details and inventory entries.");
    }
}
