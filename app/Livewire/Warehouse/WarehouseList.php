<?php

namespace App\Livewire\Warehouse;

use App\Livewire\BaseListComponent;
use App\Models\Warehouse;
use App\Http\Controllers\WarehouseController;

class WarehouseList extends BaseListComponent
{
    public $filter = 'all'; // all, active, inactive
    
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'warehouseListUpdated' => 'refreshList',
    ];

    protected function getController()
    {
        return new WarehouseController();
    }

    protected function getModel()
    {
        return Warehouse::class;
    }

    protected function getItemName()
    {
        return 'warehouses';
    }

    protected function getEventPrefix()
    {
        return 'warehouse';
    }

    protected function getViewName()
    {
        return 'livewire.warehouse.warehouse-list';
    }

    // Alias methods for backward compatibility
    public function getWarehousesProperty()
    {
        return $this->items;
    }

    public function getSelectedWarehouseProperty()
    {
        return $this->selectedItem;
    }

    public function selectWarehouse($warehouseId)
    {
        \Log::info("🔥 WarehouseList::selectWarehouse called with: {$warehouseId}");
        $this->dispatch('warehouseSelected', ['warehouseId' => $warehouseId]);
    }

    public function deleteWarehouse($warehouseId)
    {
        $this->deleteItem($warehouseId);
    }

    public function loadWarehouses()
    {
        $this->loadItems();
    }
    
    public function loadItems()
    {
        // Ensure controller is initialized
        if (!$this->controller) {
            $this->controller = $this->getController();
        }
        
        // Load warehouses with filtering
        $query = Warehouse::with(['branch', 'status', 'userCreate']);
        
        if ($this->filter === 'active') {
            $query->whereHas('status', function($q) {
                $q->where('name', 'Active');
            });
        } elseif ($this->filter === 'inactive') {
            $query->whereHas('status', function($q) {
                $q->where('name', 'Inactive');
            });
        }
        // If filter is 'all', don't add any where clause
        
        $this->items = $query->orderBy('warehouse_status_id', 'asc')
                            ->orderBy('name')
                            ->get();
        
        $this->dispatch($this->eventPrefix . 'ListUpdated');
    }
    
    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadItems();
    }
}
