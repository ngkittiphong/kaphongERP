<?php

namespace App\Livewire\Warehouse;

use App\Models\TransferSlip;
use App\Models\TransferSlipDetail;
use App\Models\TransferSlipStatus;
use App\Models\WarehouseProduct;
use App\Services\InventoryService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseTransferDetail extends Component
{
    public $transferSlip = null;
    public $transferSlipId = null;
    public $showAddForm = false;
    
    protected $inventoryService;
    public $showStatusChangeModal = false;
    public $selectedStatusId = null;
    public $selectedStatusName = null;
    public $showCancelModal = false;
    public $cancellationReason = '';
    
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
        $this->inventoryService = app(InventoryService::class);
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

    public function showCancelModal()
    {
        if ($this->canCancelTransfer()) {
            $this->showCancelModal = true;
            $this->cancellationReason = '';
        }
    }

    public function cancelTransfer()
    {
        if (!$this->canCancelTransfer()) {
            session()->flash('error', 'Transfer cannot be cancelled');
            return;
        }

        if (empty($this->cancellationReason)) {
            session()->flash('error', 'Cancellation reason is required');
            return;
        }

        try {
            DB::beginTransaction();
            
            $oldStatus = $this->transferSlip->status->name;
            $cancelledStatus = \App\Models\TransferSlipStatus::where('name', 'Cancelled')->first();
            
            if (!$cancelledStatus) {
                throw new \Exception('Cancelled status not found');
            }

            // Handle inventory restoration if needed
            if ($oldStatus === 'In Transit') {
                $this->restoreSenderWarehouseStock();
            }

            // Update transfer slip to cancelled
            $this->transferSlip->update([
                'transfer_slip_status_id' => $cancelledStatus->id,
                'note' => $this->transferSlip->note . "\n\nCANCELLED: " . $this->cancellationReason,
            ]);

            DB::commit();
            
            $this->transferSlip->refresh();
            $this->hideCancelModal();
            $this->dispatch('transferSlipListUpdated');
            
            session()->flash('message', 'Transfer cancelled successfully');
            
            Log::info("Transfer cancelled", [
                'transfer_slip_id' => $this->transferSlip->id,
                'old_status' => $oldStatus,
                'reason' => $this->cancellationReason,
                'user_id' => auth()->id()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to cancel transfer", [
                'transfer_slip_id' => $this->transferSlip->id,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Failed to cancel transfer: ' . $e->getMessage());
        }
    }

    public function hideCancelModal()
    {
        $this->showCancelModal = false;
        $this->cancellationReason = '';
    }

    public function getAllowedStatusChanges()
    {
        if (!$this->transferSlip || !$this->transferSlip->status) {
            return [];
        }

        $currentStatus = $this->transferSlip->status->name;
        
        return match($currentStatus) {
            'Pending' => \App\Models\TransferSlipStatus::whereIn('name', ['Approved', 'Cancelled'])->get(),
            'Approved' => \App\Models\TransferSlipStatus::whereIn('name', ['In Transit', 'Cancelled'])->get(),
            'In Transit' => \App\Models\TransferSlipStatus::whereIn('name', ['Delivered', 'Cancelled'])->get(),
            'Delivered' => \App\Models\TransferSlipStatus::whereIn('name', ['Completed'])->get(),
            'Completed' => collect(), // No status changes allowed - final status
            'Cancelled' => collect(), // No status changes allowed - final status
            default => collect()
        };
    }

    public function canChangeStatus()
    {
        if (!$this->transferSlip || !$this->transferSlip->status) {
            return false;
        }

        $currentStatus = $this->transferSlip->status->name;
        return !in_array($currentStatus, ['Completed', 'Cancelled']);
    }

    public function canCancelTransfer()
    {
        if (!$this->transferSlip || !$this->transferSlip->status) {
            return false;
        }

        $currentStatus = $this->transferSlip->status->name;
        return in_array($currentStatus, ['Pending', 'Approved', 'In Transit']);
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
            try {
                // Use InventoryService for stock out operation
                $result = $this->inventoryService->stockOut([
                    'warehouse_id' => $senderWarehouseId,
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->cost_per_unit ?? 0,
                    'sale_price' => $detail->cost_per_unit ?? 0,
                    'detail' => "Transfer to Warehouse #{$this->transferSlip->warehouse_destination_id} - Transfer Slip #{$this->transferSlip->transfer_slip_number}",
                    'transfer_slip_id' => $this->transferSlip->id,
                    'date_activity' => now(),
                ]);
                
                Log::info("Reduced sender warehouse stock using InventoryService", [
                    'warehouse_id' => $senderWarehouseId,
                    'product_id' => $detail->product_id,
                    'quantity_reduced' => $detail->quantity,
                    'new_balance' => $result['new_balance'],
                    'inventory_id' => $result['inventory_id']
                ]);
                
            } catch (\Exception $e) {
                Log::error("Failed to reduce sender warehouse stock", [
                    'warehouse_id' => $senderWarehouseId,
                    'product_id' => $detail->product_id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
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
            try {
                // Use InventoryService for stock in operation
                $result = $this->inventoryService->stockIn([
                    'warehouse_id' => $receiverWarehouseId,
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->cost_per_unit ?? 0,
                    'sale_price' => $detail->cost_per_unit ?? 0,
                    'detail' => "Transfer from Warehouse #{$this->transferSlip->warehouse_origin_id} - Transfer Slip #{$this->transferSlip->transfer_slip_number}",
                    'transfer_slip_id' => $this->transferSlip->id,
                    'date_activity' => now(),
                ]);
                
                Log::info("Added stock to receiver warehouse using InventoryService", [
                    'warehouse_id' => $receiverWarehouseId,
                    'product_id' => $detail->product_id,
                    'quantity_added' => $detail->quantity,
                    'new_balance' => $result['new_balance'],
                    'inventory_id' => $result['inventory_id']
                ]);
                
            } catch (\Exception $e) {
                Log::error("Failed to add stock to receiver warehouse", [
                    'warehouse_id' => $receiverWarehouseId,
                    'product_id' => $detail->product_id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        }
    }

    /**
     * Restore stock to sender warehouse when cancelling In Transit transfer
     */
    private function restoreSenderWarehouseStock()
    {
        if (!$this->transferSlip->transferSlipDetails) {
            return;
        }

        $senderWarehouseId = $this->transferSlip->warehouse_origin_id;
        
        foreach ($this->transferSlip->transferSlipDetails as $detail) {
            try {
                // Use InventoryService for stock in operation (restoration)
                $result = $this->inventoryService->stockIn([
                    'warehouse_id' => $senderWarehouseId,
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->cost_per_unit ?? 0,
                    'sale_price' => $detail->cost_per_unit ?? 0,
                    'detail' => "Transfer Cancellation - Restore to Warehouse #{$senderWarehouseId} - Transfer Slip #{$this->transferSlip->transfer_slip_number}",
                    'transfer_slip_id' => $this->transferSlip->id,
                    'date_activity' => now(),
                ]);
                
                Log::info("Restored sender warehouse stock using InventoryService", [
                    'warehouse_id' => $senderWarehouseId,
                    'product_id' => $detail->product_id,
                    'quantity_restored' => $detail->quantity,
                    'new_balance' => $result['new_balance'],
                    'inventory_id' => $result['inventory_id']
                ]);
                
            } catch (\Exception $e) {
                Log::error("Failed to restore sender warehouse stock", [
                    'warehouse_id' => $senderWarehouseId,
                    'product_id' => $detail->product_id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
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
            'Cancelled' => 'danger',
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
            'Cancelled' => 'text-danger',
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
            'Cancelled' => 'cross',
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
