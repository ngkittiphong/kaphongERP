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
        \Log::info("selectWarehouse called with ID: {$warehouseId}");
        
        // Load warehouse with relationships directly
        $warehouse = Warehouse::with(['branch', 'inventories'])->find($warehouseId);
        
        if ($warehouse) {
            \Log::info("Warehouse found: " . $warehouse->name_th);
            $this->selectedItem = $warehouse;
            \Log::info("Dispatching warehouseSelected event");
            $this->dispatch('warehouseSelected', ['warehouse' => $warehouse]);
            \Log::info("Event dispatched successfully");
        } else {
            \Log::warning("Warehouse not found with ID: {$warehouseId}");
        }
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
        $query = Warehouse::with(['branch']);
        
        if ($this->filter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->filter === 'inactive') {
            $query->where('is_active', false);
        }
        // If filter is 'all', don't add any where clause
        
        $this->items = $query->orderBy('is_active', 'desc')
                            ->orderBy('name_th')
                            ->get();
        
        $this->dispatch($this->eventPrefix . 'ListUpdated');
    }
    
    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadItems();
    }
}
