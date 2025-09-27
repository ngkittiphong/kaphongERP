<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Services\InventoryService;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\Log;

class WarehouseStockOperations extends Component
{
    public $activeTab = 'stock-in';
    public $selectedWarehouse = null;
    public $selectedProduct = null;
    
    // Recent transactions for display
    public $recentTransactions = [];
    public $showRecentTransactions = true;

    protected $listeners = ['stockOperationCompleted' => 'refreshRecentTransactions'];

    public function mount()
    {
        $this->loadRecentTransactions();
    }

    public function render()
    {
        return view('livewire.warehouse.warehouse-stock-operations');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function toggleRecentTransactions()
    {
        $this->showRecentTransactions = !$this->showRecentTransactions;
        if ($this->showRecentTransactions) {
            $this->loadRecentTransactions();
        }
    }

    public function refreshRecentTransactions($data = null)
    {
        $this->loadRecentTransactions();
    }

    private function loadRecentTransactions($limit = 10)
    {
        $this->recentTransactions = Inventory::with(['product', 'warehouse', 'moveType'])
            ->orderBy('date_activity', 'desc')
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getFormattedTransactionType($moveTypeId)
    {
        switch ($moveTypeId) {
            case 1:
                return ['type' => 'Stock In', 'class' => 'success', 'icon' => 'icon-plus-circle2'];
            case 2:
                return ['type' => 'Stock Out', 'class' => 'danger', 'icon' => 'icon-minus-circle2'];
            case 3:
                return ['type' => 'Adjustment', 'class' => 'warning', 'icon' => 'icon-wrench'];
            default:
                return ['type' => 'Unknown', 'class' => 'default', 'icon' => 'icon-question'];
        }
    }
}
