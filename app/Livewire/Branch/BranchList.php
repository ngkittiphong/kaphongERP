<?php

namespace App\Livewire\Branch;

use App\Livewire\BaseListComponent;
use App\Models\Branch;
use App\Http\Controllers\BranchController;

class BranchList extends BaseListComponent
{
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'branchListUpdated' => 'refreshList',
        'branchListRefreshRequested' => 'refreshList',
    ];

    protected function getController()
    {
        return new BranchController();
    }

    protected function getModel()
    {
        return Branch::class;
    }

    protected function getItemName()
    {
        return 'branches';
    }

    protected function getEventPrefix()
    {
        return 'branch';
    }

    protected function getViewName()
    {
        return 'livewire.branch.branch-list';
    }

    // Alias methods for backward compatibility
    public function getBranchesProperty()
    {
        return $this->items;
    }

    public function getSelectedBranchProperty()
    {
        return $this->selectedItem;
    }

    public function selectBranch($branchId)
    {
        $this->selectItem($branchId);
        $this->dispatch('BranchSelected', branchId: $branchId);
    }

    public function deleteBranch($branchId)
    {
        $this->deleteItem($branchId);
    }

    public function loadBranches()
    {
        $this->loadItems();
    }
}
