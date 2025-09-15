<?php

namespace App\Livewire\Warehouse;

use App\Models\CheckStockReport;
use App\Models\CheckStockDetail;
use Livewire\Component;

class WarehouseCheckStockDetail extends Component
{
    public $checkStockReport = null;
    public $checkStockReportId = null;
    public $showAddForm = false;
    
    protected $listeners = [
        'checkStockReportSelected' => 'loadCheckStockReport',
        'refreshComponent' => '$refresh',
        'showAddForm' => 'showAddForm',
        'hideAddForm' => 'hideAddForm',
    ];

    public function mount()
    {
        // Initialize with empty state
        $this->checkStockReport = null;
    }

    public function loadCheckStockReport($checkStockReport)
    {
        \Log::info("🔥 WarehouseCheckStockDetail::loadCheckStockReport called");
        \Log::info("🔥 Check stock report data:", ['data' => $checkStockReport]);
        
        if (is_array($checkStockReport)) {
            $this->checkStockReportId = $checkStockReport['id'] ?? null;
        } else {
            $this->checkStockReportId = $checkStockReport->id ?? null;
        }
        
        if ($this->checkStockReportId) {
            $this->checkStockReport = CheckStockReport::with([
                'userCreate',
                'warehouse',
                'checkStockDetails.product'
            ])->find($this->checkStockReportId);
        }
        
        \Log::info("🔥 Check stock report loaded:", ['loaded' => $this->checkStockReport ? 'Yes' : 'No']);
    }

    public function closeReport()
    {
        if ($this->checkStockReport) {
            $this->checkStockReport->close();
            $this->checkStockReport->refresh();
            $this->dispatch('checkStockReportListUpdated');
        }
    }

    public function openReport()
    {
        if ($this->checkStockReport) {
            $this->checkStockReport->open();
            $this->checkStockReport->refresh();
            $this->dispatch('checkStockReportListUpdated');
        }
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

    public function getDurationText($datetimeCreate)
    {
        $workDate = $this->getWorkDate($datetimeCreate);
        $expireDate = $this->getExpireDate($datetimeCreate);
        return "{$workDate} - {$expireDate}";
    }

    public function getLastCountDate($checkStockReport)
    {
        if ($checkStockReport && $checkStockReport->checkStockDetails->isNotEmpty()) {
            $lastDetail = $checkStockReport->checkStockDetails->sortByDesc('datetime_scan')->first();
            return $lastDetail->datetime_scan->format('j M Y');
        }
        return 'N/A';
    }

    public function getCountResult($productScanNum, $systemQuantity)
    {
        if ($productScanNum == $systemQuantity) {
            return 'ครบถ้วน';
        } elseif ($productScanNum < $systemQuantity) {
            return 'ขาด';
        } else {
            return 'เกิน';
        }
    }

    public function getCountResultClass($productScanNum, $systemQuantity)
    {
        if ($productScanNum == $systemQuantity) {
            return 'text-success';
        } elseif ($productScanNum < $systemQuantity) {
            return 'text-warning';
        } else {
            return 'text-warning';
        }
    }

    public function showAddForm()
    {
        $this->showAddForm = true;
        $this->checkStockReport = null;
        $this->checkStockReportId = null;
    }

    public function hideAddForm()
    {
        $this->showAddForm = false;
    }

    public function render()
    {
        return view('livewire.warehouse.warehouse-checkstock-detail');
    }
}
