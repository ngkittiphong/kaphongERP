<?php

namespace App\Livewire\Warehouse;

use App\Models\TransferSlip;
use Livewire\Component;

class WarehouseTransferDetail extends Component
{
    public $transferSlip = null;
    public $transferSlipId = null;
    
    protected $listeners = [
        'transferSlipSelected' => 'loadTransferSlip',
        'refreshComponent' => '$refresh',
    ];

    public function mount()
    {
        // Initialize with empty state
        $this->transferSlip = null;
    }

    public function loadTransferSlip($transferSlip)
    {
        \Log::info("🔥 WarehouseTransferDetail::loadTransferSlip called");
        \Log::info("🔥 Transfer slip data: " . json_encode($transferSlip));
        
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
        
        \Log::info("🔥 Transfer slip loaded: " . ($this->transferSlip ? 'Yes' : 'No'));
    }

    public function updateStatus($statusId)
    {
        if ($this->transferSlip) {
            $this->transferSlip->update([
                'transfer_slip_status_id' => $statusId,
                'user_receive_id' => auth()->id(),
                'user_receive_name' => auth()->user()->username,
                'date_receive' => now(),
            ]);
            
            $this->transferSlip->refresh();
            $this->dispatch('transferSlipListUpdated');
        }
    }

    public function getStatusColor($statusName)
    {
        return match($statusName) {
            'Pending' => 'warning',
            'In Transit' => 'info', 
            'Delivered' => 'success',
            'Cancelled' => 'danger',
            'Returned' => 'secondary',
            default => 'secondary'
        };
    }

    public function getStatusTextColor($statusName)
    {
        return match($statusName) {
            'Pending' => 'text-warning',
            'In Transit' => 'text-info',
            'Delivered' => 'text-success', 
            'Cancelled' => 'text-danger',
            'Returned' => 'text-secondary',
            default => 'text-secondary'
        };
    }

    public function getStatusIcon($statusName)
    {
        return match($statusName) {
            'Pending' => 'clock',
            'In Transit' => 'truck',
            'Delivered' => 'checkmark-circle',
            'Cancelled' => 'cross',
            'Returned' => 'undo',
            default => 'help'
        };
    }

    public function render()
    {
        return view('livewire.warehouse.warehouse-transfer-detail');
    }
}
