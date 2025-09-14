<?php

namespace App\Livewire\Warehouse;

use App\Livewire\BaseListComponent;
use App\Models\Warehouse;
use App\Http\Controllers\WarehouseController;

class WarehouseList extends BaseListComponent
{
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
        $this->selectItem($warehouseId);
    }

    public function deleteWarehouse($warehouseId)
    {
        $this->deleteItem($warehouseId);
    }

    public function loadWarehouses()
    {
        $this->loadItems();
    }
}
