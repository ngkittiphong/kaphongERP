<?php

namespace App\Livewire\Warehouse;

use App\Models\TransferSlip;
use App\Models\WarehouseProduct;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseTransferDetail extends Component
{
    public $transferSlip = null;
    public $transferSlipId = null;
    public $showAddForm = false;
    public $showStatusChangeModal = false;
    public $selectedStatusId = null;
    public $selectedStatusName = null;
    
    protected $listeners = [
        'transferSlipSelected' => 'loadTransferSlip',
        'refreshComponent' => '$refresh',
        'showAddForm' => 'showAddForm',
        'hideAddForm' => 'hideAddForm',
    ];

    public function mount()
    {
        // Initialize with empty state
        $this->transferSlip = null;
    }

    public function loadTransferSlip($transferSlip)
    {
        \Log::info("🔥 WarehouseTransferDetail::loadTransferSlip called");
        \Log::info("🔥 Transfer slip data:", ['data' => $transferSlip]);
        
        if (is_array($transferSlip)) {
            $this->transferSlipId = $transferSlip['id'] ?? null;
        } else {
            $this->transferSlipId = $transferSlip->id ?? null;
        }
        
        if ($this->transferSlipId) {
            $this->transferSlip = TransferSlip::with([
                'userRequest',
                'userReceive',
                'warehouseOrigin',
                'warehouseDestination',
                'status',
                'transferSlipDetails.product'
            ])->find($this->transferSlipId);
        }
        
        \Log::info("🔥 Transfer slip loaded:", ['loaded' => $this->transferSlip ? 'Yes' : 'No']);
    }

    public function updateStatus($statusId)
    {
        if ($this->transferSlip) {
            $oldStatus = $this->transferSlip->status->name ?? '';
            $newStatus = \App\Models\TransferSlipStatus::find($statusId)->name ?? '';
            
            try {
                DB::beginTransaction();
                
                // Update transfer slip status
                $this->transferSlip->update([
                    'transfer_slip_status_id' => $statusId,
                    'user_receive_id' => auth()->id(),
                    'user_receive_name' => auth()->user()->username,
                    'date_receive' => now(),
                ]);
                
                // Handle inventory updates based on status changes
                $this->handleInventoryUpdates($oldStatus, $newStatus);
                
                DB::commit();
                
                $this->transferSlip->refresh();
                $this->dispatch('transferSlipListUpdated');
                
                Log::info("Transfer slip status updated", [
                    'transfer_slip_id' => $this->transferSlip->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'user_id' => auth()->id()
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to update transfer slip status", [
                    'transfer_slip_id' => $this->transferSlip->id,
                    'error' => $e->getMessage()
                ]);
                
                session()->flash('error', 'Failed to update status: ' . $e->getMessage());
            }
        }
    }

    public function confirmStatusChange($statusId)
    {
        $status = \App\Models\TransferSlipStatus::find($statusId);
        if ($status) {
            $this->selectedStatusId = $statusId;
            $this->selectedStatusName = $status->name;
            $this->showStatusChangeModal = true;
        }
    }

    public function confirmStatusUpdate()
    {
        if ($this->transferSlip && $this->selectedStatusId) {
            $this->updateStatus($this->selectedStatusId);
            $this->cancelStatusChange();
            
            // Show success message
            session()->flash('message', 'Transfer slip status updated successfully!');
        }
    }

    public function cancelStatusChange()
    {
        $this->showStatusChangeModal = false;
        $this->selectedStatusId = null;
        $this->selectedStatusName = null;
    }

    public function getAllowedStatusChanges()
    {
        if (!$this->transferSlip || !$this->transferSlip->status) {
            return [];
        }

        $currentStatus = $this->transferSlip->status->name;
        
        return match($currentStatus) {
            'Pending' => \App\Models\TransferSlipStatus::whereIn('name', ['Approved'])->get(),
            'Approved' => \App\Models\TransferSlipStatus::whereIn('name', ['In Transit'])->get(),
            'In Transit' => \App\Models\TransferSlipStatus::whereIn('name', ['Delivered'])->get(),
            'Delivered' => \App\Models\TransferSlipStatus::whereIn('name', ['Completed'])->get(),
            'Completed' => collect(), // No status changes allowed - final status
            default => collect()
        };
    }

    public function canChangeStatus()
    {
        if (!$this->transferSlip || !$this->transferSlip->status) {
            return false;
        }

        $currentStatus = $this->transferSlip->status->name;
        return !in_array($currentStatus, ['Completed']);
    }

    /**
     * Handle inventory updates based on status changes
     */
    private function handleInventoryUpdates($oldStatus, $newStatus)
    {
        // Approved → In Transit: Reduce stock in sender warehouse
        if ($oldStatus === 'Approved' && $newStatus === 'In Transit') {
            $this->reduceSenderWarehouseStock();
        }
        
        // In Transit → Delivered: Add stock to receiver warehouse
        if ($oldStatus === 'In Transit' && $newStatus === 'Delivered') {
            $this->addReceiverWarehouseStock();
        }
    }

    /**
     * Reduce stock in sender warehouse when status changes to In Transit
     */
    private function reduceSenderWarehouseStock()
    {
        if (!$this->transferSlip->transferSlipDetails) {
            return;
        }

        $senderWarehouseId = $this->transferSlip->warehouse_origin_id;
        
        foreach ($this->transferSlip->transferSlipDetails as $detail) {
            $warehouseProduct = WarehouseProduct::where('warehouse_id', $senderWarehouseId)
                ->where('product_id', $detail->product_id)
                ->first();

            if ($warehouseProduct) {
                // Check if sufficient stock is available
                if ($warehouseProduct->balance < $detail->quantity) {
                    throw new \Exception("Insufficient stock for product {$detail->product->name}. Available: {$warehouseProduct->balance}, Required: {$detail->quantity}");
                }

                // Reduce the balance
                $warehouseProduct->adjustBalance(-$detail->quantity);
                
                Log::info("Reduced sender warehouse stock", [
                    'warehouse_id' => $senderWarehouseId,
                    'product_id' => $detail->product_id,
                    'quantity_reduced' => $detail->quantity,
                    'new_balance' => $warehouseProduct->balance
                ]);
            } else {
                throw new \Exception("Product {$detail->product->name} not found in sender warehouse");
            }
        }
    }

    /**
     * Add stock to receiver warehouse when status changes to Delivered
     */
    private function addReceiverWarehouseStock()
    {
        if (!$this->transferSlip->transferSlipDetails) {
            return;
        }

        $receiverWarehouseId = $this->transferSlip->warehouse_destination_id;
        
        foreach ($this->transferSlip->transferSlipDetails as $detail) {
            $warehouseProduct = WarehouseProduct::where('warehouse_id', $receiverWarehouseId)
                ->where('product_id', $detail->product_id)
                ->first();

            if ($warehouseProduct) {
                // Add to existing warehouse product
                $warehouseProduct->adjustBalance($detail->quantity);
                
                Log::info("Added stock to receiver warehouse", [
                    'warehouse_id' => $receiverWarehouseId,
                    'product_id' => $detail->product_id,
                    'quantity_added' => $detail->quantity,
                    'new_balance' => $warehouseProduct->balance
                ]);
            } else {
                // Create new warehouse product entry
                WarehouseProduct::create([
                    'warehouse_id' => $receiverWarehouseId,
                    'product_id' => $detail->product_id,
                    'balance' => $detail->quantity,
                    'avr_buy_price' => $detail->cost_per_unit ?? 0,
                    'avr_sale_price' => $detail->cost_per_unit ?? 0,
                    'avr_remain_price' => $detail->cost_per_unit ?? 0,
                ]);
                
                Log::info("Created new warehouse product entry", [
                    'warehouse_id' => $receiverWarehouseId,
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity
                ]);
            }
        }
    }

    public function getStatusColor($statusName)
    {
        return match($statusName) {
            'Pending' => 'warning',
            'Approved' => 'primary',
            'In Transit' => 'info', 
            'Delivered' => 'success',
            'Completed' => 'success',
            default => 'secondary'
        };
    }

    public function getStatusTextColor($statusName)
    {
        return match($statusName) {
            'Pending' => 'text-warning',
            'Approved' => 'text-primary',
            'In Transit' => 'text-info',
            'Delivered' => 'text-success', 
            'Completed' => 'text-success',
            default => 'text-secondary'
        };
    }

    public function getStatusIcon($statusName)
    {
        return match($statusName) {
            'Pending' => 'clock',
            'Approved' => 'checkmark2',
            'In Transit' => 'truck',
            'Delivered' => 'checkmark-circle',
            'Completed' => 'checkmark-circle2',
            default => 'help'
        };
    }

    public function showAddForm()
    {
        $this->showAddForm = true;
        $this->transferSlip = null;
        $this->transferSlipId = null;
    }

    public function hideAddForm()
    {
        $this->showAddForm = false;
    }

    public function render()
    {
        return view('livewire.warehouse.warehouse-transfer-detail');
    }
}
