<?php

namespace App\Livewire\Branch;

use Livewire\Component;

class BranchAddBranchBtn extends Component
{
    public function addBranch()
    {
        $this->dispatch('showAddBranchForm');
    }

    public function render()
    {
        return view('livewire.branch.branch-add-branch-btn');
    }
}
