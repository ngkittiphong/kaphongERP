<?php

namespace App\Livewire\Warehouse;

use App\Livewire\BaseListComponent;
use App\Models\TransferSlip;
use App\Http\Controllers\TransferSlipController;

class WarehouseTransferList extends BaseListComponent
{
    public $filter = 'all'; // all, pending, completed, cancelled
    public $selectedTransferSlip = null;
    
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'transferSlipListUpdated' => 'refreshList',
        'ProfileSelected' => 'handleProfileSelected',
    ];

    protected function getController()
    {
        return new TransferSlipController();
    }

    protected function getModel()
    {
        return TransferSlip::class;
    }

    protected function getItemName()
    {
        return 'transferSlips';
    }

    protected function getEventPrefix()
    {
        return 'transferSlip';
    }

    protected function getViewName()
    {
        return 'livewire.warehouse.warehouse-transfer-list';
    }

    public function mount()
    {
        parent::mount();
        $this->selectedTransferSlip = null;
    }

    public function loadItems()
    {
        // Ensure controller is initialized
        if (!$this->controller) {
            $this->controller = $this->getController();
        }
        
        // Load transfer slips with filtering
        $query = TransferSlip::with([
            'userRequest',
            'userReceive',
            'warehouseOrigin',
            'warehouseDestination', 
            'status',
            'transferSlipDetails'
        ]);
        
        // Apply status filter
        if ($this->filter === 'pending') {
            $query->whereHas('status', function($q) {
                $q->whereIn('name', ['Pending', 'Approved', 'In Transit']);
            });
        } elseif ($this->filter === 'completed') {
            $query->whereHas('status', function($q) {
                $q->whereIn('name', ['Delivered', 'Completed']);
            });
        } elseif ($this->filter === 'cancelled') {
            $query->whereHas('status', function($q) {
                $q->where('name', 'Cancelled');
            });
        }
        // If filter is 'all', don't add any where clause
        
        $this->items = $query->orderBy('date_request', 'desc')->get();
        
        $this->dispatch($this->eventPrefix . 'ListUpdated');
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadItems();
    }

    public function selectTransferSlip($transferSlipId)
    {
        \Log::info("🔥 WarehouseTransferList::selectTransferSlip called with: {$transferSlipId}");
        
        try {
            $transferSlip = TransferSlip::with([
                'userRequest',
                'userReceive',
                'warehouseOrigin',
                'warehouseDestination',
                'status',
                'transferSlipDetails.product'
            ])->find($transferSlipId);
            
            if ($transferSlip) {
                $this->selectedTransferSlip = $transferSlip;
                \Log::info("🔥 About to dispatch transferSlipSelected event");
                $this->dispatch('transferSlipSelected', $this->selectedTransferSlip);
                \Log::info("🔥 transferSlipSelected event dispatched");
            }
        } catch (\Exception $e) {
            \Log::error("🔥 Error in selectTransferSlip: " . $e->getMessage());
        }
    }

    public function handleProfileSelected($data)
    {
        // Handle profile selection if needed
        \Log::info("🔥 ProfileSelected event received:", ['data' => $data]);
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
            'Returned' => 'secondary',
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
            'Returned' => 'text-secondary',
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
            'Returned' => 'undo',
            default => 'help'
        };
    }

    public function getStatusBadgeColor($statusName)
    {
        return match($statusName) {
            'Pending' => 'warning',
            'Approved' => 'primary',
            'In Transit' => 'info',
            'Delivered' => 'success',
            'Completed' => 'success',
            'Cancelled' => 'danger',
            'Returned' => 'secondary',
            default => 'secondary'
        };
    }
}
