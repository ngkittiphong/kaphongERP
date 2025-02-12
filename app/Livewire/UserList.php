<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserList extends Component
{
    public $users;
    public $selectedUserId;

    protected $listeners = ['userSelected'];

    public function mount()
    {
        $this->users = User::all();
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $this->dispatch('userSelected', userId: $userId);
    }

    public function render()
    {
        return view('livewire.user-list');
    }
}