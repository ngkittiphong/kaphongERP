<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;

class WarehouseAddNewTransfer extends Component
{
    public function addNewTransfer()
    {
        $this->dispatch('showAddNewTransferForm');
    }

    public function render()
    {
        return view('livewire.warehouse.warehouse-add-new-transfer');
    }
}
