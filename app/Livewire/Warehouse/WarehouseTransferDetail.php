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
        'showAddFormWithPreselection' => 'showAddFormWithPreselection',
        'hideAddForm' => 'hideAddForm',
    ];

    protected $rules = [
        'cancellationReason' => 'required|string|min:10|max:500',
    ];

    protected $messages = [
        'cancellationReason.required' => 'Cancellation reason is required.',
        'cancellationReason.min' => 'Cancellation reason must be at least 10 characters.',
        'cancellationReason.max' => 'Cancellation reason must not exceed 500 characters.',
    ];

    public function mount()
    {
        // Initialize with empty state
        $this->transferSlip = null;
        
        // Initialize inventory service
        try {
            $this->inventoryService = app(InventoryService::class);
            Log::info("🔥 WarehouseTransferDetail mounted", [
                'inventory_service_initialized' => $this->inventoryService ? 'yes' : 'no'
            ]);
        } catch (\Exception $e) {
            Log::error("🔥 Failed to initialize InventoryService", [
                'error' => $e->getMessage()
            ]);
            $this->inventoryService = null;
        }
        
        // Check for preselection data and show add form automatically
        $preselectionData = session('transfer_preselection');
        if ($preselectionData) {
            \Log::info('🔥 WarehouseTransferDetail: Found preselection data, showing add form automatically');
            $this->showAddForm = true;
        }
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
            
            \Log::info("🔥 Transfer slip loaded successfully:", [
                'id' => $this->transferSlip->id ?? 'null',
                'number' => $this->transferSlip->transfer_slip_number ?? 'null',
                'status' => $this->transferSlip->status->name ?? 'null'
            ]);
        }
        
        // Hide the add form when a transfer slip is selected
        $this->showAddForm = false;
        
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
                $updateData = [
                    'transfer_slip_status_id' => $statusId,
                    'user_receive_id' => auth()->id(),
                    'user_receive_name' => auth()->user()->username,
                    'date_receive' => now(),
                ];
                
                // If changing to Cancelled status, add cancellation reason to note
                if ($newStatus === 'Cancelled' && !empty($this->cancellationReason)) {
                    $updateData['note'] = $this->transferSlip->note . "\n\nCANCELLED: " . $this->cancellationReason;
                }
                
                $this->transferSlip->update($updateData);
                
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
                
                // Re-throw the exception to be handled by the calling method
                throw $e;
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
        Log::info("🔥 confirmStatusUpdate called", [
            'transfer_slip_id' => $this->transferSlip ? $this->transferSlip->id : 'null',
            'selected_status_id' => $this->selectedStatusId,
            'selected_status_name' => $this->selectedStatusName,
            'cancellation_reason' => $this->cancellationReason
        ]);
        
        if ($this->transferSlip && $this->selectedStatusId) {
            // If changing to Cancelled status, validate cancellation reason
            if ($this->selectedStatusName === 'Cancelled') {
                Log::info("🔥 Validating cancellation reason", [
                    'reason_length' => strlen($this->cancellationReason ?? ''),
                    'reason_content' => $this->cancellationReason
                ]);
                
                // Check if cancellation reason is provided
                if (empty(trim($this->cancellationReason))) {
                    Log::error("🔥 Cancellation reason is empty");
                    $this->addError('cancellationReason', 'Cancellation reason is required.');
                    session()->flash('error', __t('transfer.please_provide_cancellation_reason', 'Please provide a cancellation reason.'));
                    return;
                }
                
                // Check minimum length
                if (strlen(trim($this->cancellationReason)) < 10) {
                    Log::error("🔥 Cancellation reason too short", ['length' => strlen($this->cancellationReason)]);
                    $this->addError('cancellationReason', 'Cancellation reason must be at least 10 characters.');
                    session()->flash('error', __t('transfer.cancellation_reason_too_short', 'Cancellation reason must be at least 10 characters.'));
                    return;
                }
                
                // Check maximum length
                if (strlen($this->cancellationReason) > 500) {
                    Log::error("🔥 Cancellation reason too long", ['length' => strlen($this->cancellationReason)]);
                    $this->addError('cancellationReason', 'Cancellation reason must not exceed 500 characters.');
                    session()->flash('error', __t('transfer.cancellation_reason_too_long', 'Cancellation reason must not exceed 500 characters.'));
                    return;
                }
                
                Log::info("🔥 Cancellation reason validation passed");
            }
            
            Log::info("🔥 Proceeding with status update");
            
            try {
                $this->updateStatus($this->selectedStatusId);
                
                // Close the modal first
                $this->cancelStatusChange();
                
                // Show success message only if update succeeded
                session()->flash('message', __t('transfer.status_updated_successfully', 'Transfer slip status updated successfully!'));
                Log::info("🔥 Status update completed successfully");
            } catch (\Exception $e) {
                Log::error("🔥 Status update failed in confirmStatusUpdate", [
                    'error' => $e->getMessage(),
                    'transfer_slip_id' => $this->transferSlip->id
                ]);
                
                // Close the modal even on error
                $this->cancelStatusChange();
                
                // Check for specific error messages and translate them
                $errorMessage = $e->getMessage();
                if (strpos($errorMessage, 'Stock Out failed: Product not found in warehouse') !== false) {
                    $errorMessage = __t('inventory.stock_out_product_not_found', 'Stock Out failed: Product not found in warehouse');
                }
                
                session()->flash('error', __t('transfer.failed_to_update_status', 'Failed to update status') . ': ' . $errorMessage);
            }
        } else {
            Log::warning("🔥 Cannot update status - missing transfer slip or selected status", [
                'has_transfer_slip' => $this->transferSlip ? 'yes' : 'no',
                'has_selected_status' => $this->selectedStatusId ? 'yes' : 'no'
            ]);
        }
    }

    public function cancelStatusChange()
    {
        Log::info("🔥 cancelStatusChange called");
        
        $this->showStatusChangeModal = false;
        $this->selectedStatusId = null;
        $this->selectedStatusName = null;
        $this->cancellationReason = '';
        
        Log::info("🔥 Status change modal cancelled and cleared", [
            'showStatusChangeModal' => $this->showStatusChangeModal,
            'selectedStatusId' => $this->selectedStatusId,
            'selectedStatusName' => $this->selectedStatusName
        ]);
    }

    public function openCancelModal()
    {
        Log::info("🔥 openCancelModal called", [
            'transfer_slip_id' => $this->transferSlip ? $this->transferSlip->id : 'null',
            'can_cancel' => $this->canCancelTransfer(),
            'current_status' => $this->transferSlip && $this->transferSlip->status ? $this->transferSlip->status->name : 'null'
        ]);
        
        if ($this->canCancelTransfer()) {
            // Find the "Cancelled" status and show status change modal
            $cancelledStatus = \App\Models\TransferSlipStatus::where('name', 'Cancelled')->first();
            
            if ($cancelledStatus) {
                Log::info("🔥 Found cancelled status, showing status change modal", [
                    'cancelled_status_id' => $cancelledStatus->id
                ]);
                
                $this->selectedStatusId = $cancelledStatus->id;
                $this->selectedStatusName = $cancelledStatus->name;
                $this->showStatusChangeModal = true;
                $this->cancellationReason = ''; // Clear any previous cancellation reason
                
                Log::info("🔥 Status change modal shown for cancellation", [
                    'cancelled_status_id' => $cancelledStatus->id,
                    'cancellation_reason_cleared' => empty($this->cancellationReason)
                ]);
            } else {
                Log::error("🔥 Cancelled status not found in database");
                session()->flash('error', __t('transfer.cancelled_status_not_found', 'Cancelled status not found. Please contact administrator.'));
            }
        } else {
            Log::warning("🔥 Cannot show cancel modal - transfer cannot be cancelled");
            session()->flash('error', __t('transfer.cannot_be_cancelled', 'Transfer cannot be cancelled in its current status'));
        }
    }

    public function cancelTransfer()
    {
        if (!$this->canCancelTransfer()) {
            session()->flash('error', __t('transfer.cannot_be_cancelled', 'Transfer cannot be cancelled'));
            return;
        }

        try {
            // Validate the cancellation reason
            $this->validate(['cancellationReason' => 'required|string|min:10|max:500']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Cancellation validation failed", [
                'transfer_slip_id' => $this->transferSlip->id,
                'errors' => $e->errors()
            ]);
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
            
            session()->flash('message', __t('transfer.cancelled_successfully', 'Transfer cancelled successfully'));
            
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
            
            session()->flash('error', __t('transfer.failed_to_cancel', 'Failed to cancel transfer') . ': ' . $e->getMessage());
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
            'Pending' => \App\Models\TransferSlipStatus::whereIn('name', ['In Transit', 'Cancelled'])->get(),
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
            Log::warning("🔥 canCancelTransfer: No transfer slip or status found");
            return false;
        }

        $currentStatus = $this->transferSlip->status->name;
        $canCancel = in_array($currentStatus, ['Pending', 'In Transit']);
        
        Log::info("🔥 canCancelTransfer check", [
            'transfer_slip_id' => $this->transferSlip->id,
            'current_status' => $currentStatus,
            'can_cancel' => $canCancel,
            'allowed_statuses' => ['Pending', 'In Transit']
        ]);
        
        return $canCancel;
    }

    /**
     * Handle inventory updates based on status changes
     */
    private function handleInventoryUpdates($oldStatus, $newStatus)
    {
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
        Log::info("🔥 reduceSenderWarehouseStock called", [
            'inventory_service_exists' => $this->inventoryService ? 'yes' : 'no',
            'transfer_slip_id' => $this->transferSlip ? $this->transferSlip->id : 'null'
        ]);
        
        if (!$this->transferSlip->transferSlipDetails) {
            Log::warning("🔥 No transfer slip details found");
            return;
        }

        $senderWarehouseId = $this->transferSlip->warehouse_origin_id;
        
        foreach ($this->transferSlip->transferSlipDetails as $detail) {
            try {
                // Ensure inventory service is available
                if (!$this->inventoryService) {
                    Log::error("🔥 InventoryService is null, attempting to reinitialize");
                    $this->inventoryService = app(InventoryService::class);
                }
                
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
                // Ensure inventory service is available
                if (!$this->inventoryService) {
                    Log::error("🔥 InventoryService is null, attempting to reinitialize");
                    $this->inventoryService = app(InventoryService::class);
                }
                
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
                // Ensure inventory service is available
                if (!$this->inventoryService) {
                    Log::error("🔥 InventoryService is null, attempting to reinitialize");
                    $this->inventoryService = app(InventoryService::class);
                }
                
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
            'In Transit' => 'truck',
            'Delivered' => 'checkmark-circle',
            'Completed' => 'checkmark-circle2',
            'Cancelled' => 'cross',
            default => 'help'
        };
    }

    /**
     * Get translated status name for display
     */
    public function getTranslatedStatusName($statusName)
    {
        return \App\Models\TransferSlipStatus::getTranslatedName($statusName);
    }

    /**
     * Get translated selected status name
     */
    public function getSelectedStatusTranslatedNameProperty()
    {
        return $this->selectedStatusName ? $this->getTranslatedStatusName($this->selectedStatusName) : null;
    }

    public function showAddForm()
    {
        $this->showAddForm = true;
        $this->transferSlip = null;
        $this->transferSlipId = null;
    }

    public function showAddFormWithPreselection($data)
    {
        \Log::info('🔥 WarehouseTransferDetail: showAddFormWithPreselection called', $data);
        $this->showAddForm = true;
        $this->transferSlip = null;
        $this->transferSlipId = null;
        
        // Dispatch the preselection data to the add form component
        $this->dispatch('showAddFormWithPreselection', $data);
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
