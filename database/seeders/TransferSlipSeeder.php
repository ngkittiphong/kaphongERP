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
use App\Models\Company;

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

        // Get company with ID 1
        $company = Company::find(1);
        if (!$company) {
            $this->command->error('Company with ID 1 not found. Please run CompanyBranchSeeder first.');
            return;
        }

        $transferSlipsCreated = 0;
        $createdTransferSlips = [];

        // Define status distribution for realistic scenarios
        $statusDistribution = [
            'Pending' => 5,      // 25% - New requests
            'Approved' => 4,     // 20% - Approved but not yet shipped
            'In Transit' => 3,   // 15% - Currently being transported
            'Delivered' => 3,    // 15% - Arrived but not yet completed
            'Completed' => 5,    // 25% - Fully processed
        ];

        $this->command->info('Starting TransferSlipSeeder with new TF pattern...');

        // Create transfer slips
        for ($i = 0; $i < 20; $i++) {
            $originWarehouse = $warehouses->random();
            $destinationWarehouse = $warehouses->where('id', '!=', $originWarehouse->id)->random();
            $requestUser = $users->random();
            $receiveUser = $users->random();
            
            // Select status based on distribution
            $statusName = $this->selectStatusByDistribution($statusDistribution);
            $status = $statuses->where('name', $statusName)->first();

            // Calculate dates based on status for realistic workflow
            $dateRequest = now()->subDays(rand(1, 30));
            $dateReceive = null;
            $userReceiveName = null;
            
            // Set receive date and user based on status
            if (in_array($statusName, ['Delivered', 'Completed'])) {
                $dateReceive = $dateRequest->copy()->addDays(rand(1, 7)); // 1-7 days after request
                $userReceiveName = $receiveUser->name ?? 'User ' . $receiveUser->id;
            }

            // Generate transfer slip number using the new pattern
            $transferSlipNumber = TransferSlip::generateTransferSlipNumber();
            
            $transferSlip = TransferSlip::create([
                'user_request_id' => $requestUser->id,
                'user_receive_id' => $receiveUser->id,
                'transfer_slip_number' => $transferSlipNumber,
                'company_name' => $company->company_name_th,
                'company_address' => 'Address ' . ($i + 1) . ', City, Country',
                'tax_id' => $company->tax_no,
                'tel' => '0' . rand(100000000, 999999999),
                'date_request' => $dateRequest,
                'user_request_name' => $requestUser->name ?? 'User ' . $requestUser->id,
                'deliver_name' => 'Delivery Person ' . ($i + 1),
                'date_receive' => $dateReceive,
                'user_receive_name' => $userReceiveName,
                'warehouse_origin_id' => $originWarehouse->id,
                'warehouse_origin_name' => $originWarehouse->name,
                'warehouse_destination_id' => $destinationWarehouse->id,
                'warehouse_destination_name' => $destinationWarehouse->name,
                'total_quantity' => 0, // Will be calculated from details
                'transfer_slip_status_id' => $status->id,
                'description' => 'Transfer description ' . ($i + 1),
                'note' => 'Transfer note ' . ($i + 1),
            ]);

            // Store transfer slip info for logging
            $createdTransferSlips[] = [
                'id' => $transferSlip->id,
                'transfer_slip_number' => $transferSlipNumber,
                'status' => $statusName,
                'origin_warehouse' => $originWarehouse->name,
                'destination_warehouse' => $destinationWarehouse->name,
                'date_request' => $dateRequest->format('Y-m-d')
            ];

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

                // Only create inventory entries for completed transfers
                // This follows the new workflow where inventory is updated via status changes
                if ($statusName === 'Completed') {
                    $moveType = $moveTypes->random();
                    
                    // Outbound inventory entry (from origin warehouse) - when status was "In Transit"
                    \App\Models\Inventory::create([
                        'product_id' => $product->id,
                        'warehouse_id' => $originWarehouse->id,
                        'transfer_slip_id' => $transferSlip->id,
                        'date_activity' => $transferSlip->date_request->addDays(rand(1, 3)), // When it went to In Transit
                        'move_type_id' => $moveType->id,
                        'detail' => 'Transfer out - ' . $transferSlip->transfer_slip_number,
                        'quantity_move' => -$quantity, // Negative for outbound
                        'unit_name' => $product->unit_name ?? 'pcs',
                        'remaining' => rand(0, 100), // Random remaining stock
                        'avr_buy_price' => $costPerUnit,
                        'avr_sale_price' => $costPerUnit * 1.2, // 20% markup
                        'avr_remain_price' => $costPerUnit,
                    ]);

                    // Inbound inventory entry (to destination warehouse) - when status was "Delivered"
                    \App\Models\Inventory::create([
                        'product_id' => $product->id,
                        'warehouse_id' => $destinationWarehouse->id,
                        'transfer_slip_id' => $transferSlip->id,
                        'date_activity' => $transferSlip->date_receive ?? $transferSlip->date_request->addDays(rand(4, 7)),
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
            }

            // Update total quantity
            $transferSlip->update(['total_quantity' => $totalQuantity]);

            $transferSlipsCreated++;
        }

        $this->command->info("Created {$transferSlipsCreated} transfer slips with details and inventory entries.");
        
        // Display generated transfer slip numbers
        $this->command->info('Generated Transfer Slip Numbers:');
        foreach ($createdTransferSlips as $transferSlip) {
            $this->command->line("  - {$transferSlip['transfer_slip_number']} ({$transferSlip['status']}) - {$transferSlip['origin_warehouse']} → {$transferSlip['destination_warehouse']} ({$transferSlip['date_request']})");
        }
    }

    /**
     * Select a status based on the distribution array
     */
    private function selectStatusByDistribution(array $distribution): string
    {
        $total = array_sum($distribution);
        $random = rand(1, $total);
        
        $current = 0;
        foreach ($distribution as $status => $count) {
            $current += $count;
            if ($random <= $current) {
                return $status;
            }
        }
        
        return 'Pending'; // Fallback
    }
}
