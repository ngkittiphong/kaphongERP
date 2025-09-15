<?php

namespace App\Livewire\Warehouse;

use App\Livewire\BaseListComponent;
use App\Models\CheckStockReport;
use App\Models\Warehouse;

class WarehouseCheckStockList extends BaseListComponent
{
    public $filter = 'all'; // all, pending, completed, expired
    public $selectedCheckStockReport = null;
    
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'checkStockReportListUpdated' => 'refreshList',
        'ProfileSelected' => 'handleProfileSelected',
    ];

    protected function getController()
    {
        return null; // No specific controller needed for check stock reports
    }

    protected function getModel()
    {
        return CheckStockReport::class;
    }

    protected function getItemName()
    {
        return 'checkStockReports';
    }

    protected function getEventPrefix()
    {
        return 'checkStockReport';
    }

    protected function getViewName()
    {
        return 'livewire.warehouse.warehouse-checkstock-list';
    }

    public function mount()
    {
        parent::mount();
        $this->selectedCheckStockReport = null;
    }

    public function loadItems()
    {
        // Load check stock reports with filtering
        $query = CheckStockReport::with([
            'userCreate',
            'warehouse',
            'checkStockDetails.product'
        ]);
        
        // Apply status filter
        if ($this->filter === 'pending') {
            $query->where('closed', false);
        } elseif ($this->filter === 'completed') {
            $query->where('closed', true);
        } elseif ($this->filter === 'expired') {
            // Reports that are not closed and older than 7 days
            $query->where('closed', false)
                  ->where('datetime_create', '<', now()->subDays(7));
        }
        // If filter is 'all', don't add any where clause
        
        $this->items = $query->orderBy('datetime_create', 'desc')->get();
        
        $this->dispatch($this->eventPrefix . 'ListUpdated');
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadItems();
    }

    public function selectCheckStockReport($checkStockReportId)
    {
        \Log::info("🔥 WarehouseCheckStockList::selectCheckStockReport called with: {$checkStockReportId}");
        
        try {
            $checkStockReport = CheckStockReport::with([
                'userCreate',
                'warehouse',
                'checkStockDetails.product'
            ])->find($checkStockReportId);
            
            if ($checkStockReport) {
                $this->selectedCheckStockReport = $checkStockReport;
                \Log::info("🔥 About to dispatch checkStockReportSelected event");
                $this->dispatch('checkStockReportSelected', $this->selectedCheckStockReport);
                \Log::info("🔥 checkStockReportSelected event dispatched");
            }
        } catch (\Exception $e) {
            \Log::error("🔥 Error in selectCheckStockReport: " . $e->getMessage());
        }
    }

    public function handleProfileSelected($data)
    {
        // Handle profile selection if needed
        \Log::info("🔥 ProfileSelected event received:", ['data' => $data]);
    }

    public function getStatusColor($isClosed, $datetimeCreate)
    {
        if ($isClosed) {
            return 'success';
        }
        
        // Check if expired (older than 7 days)
        if ($datetimeCreate < now()->subDays(7)) {
            return 'warning';
        }
        
        return 'info';
    }

    public function getStatusTextColor($isClosed, $datetimeCreate)
    {
        if ($isClosed) {
            return 'text-success';
        }
        
        // Check if expired (older than 7 days)
        if ($datetimeCreate < now()->subDays(7)) {
            return 'text-warning';
        }
        
        return 'text-info';
    }

    public function getStatusBadgeColor($isClosed, $datetimeCreate)
    {
        if ($isClosed) {
            return 'success';
        }
        
        // Check if expired (older than 7 days)
        if ($datetimeCreate < now()->subDays(7)) {
            return 'warning';
        }
        
        return 'info';
    }

    public function getStatusIcon($isClosed, $datetimeCreate)
    {
        if ($isClosed) {
            return 'checkmark-circle';
        }
        
        // Check if expired (older than 7 days)
        if ($datetimeCreate < now()->subDays(7)) {
            return 'clock';
        }
        
        return 'play';
    }

    public function getStatusText($isClosed, $datetimeCreate)
    {
        if ($isClosed) {
            return 'complete';
        }
        
        // Check if expired (older than 7 days)
        if ($datetimeCreate < now()->subDays(7)) {
            return 'work expire';
        }
        
        return 'in process';
    }

    public function getWorkDate($datetimeCreate)
    {
        return $datetimeCreate->format('j M Y');
    }

    public function getExpireDate($datetimeCreate)
    {
        return $datetimeCreate->addDays(7)->format('j M Y');
    }
}
