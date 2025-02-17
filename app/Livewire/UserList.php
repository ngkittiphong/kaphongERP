<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserList extends Component
{
    public $users;
    public $selectedUserId;

    protected $listeners = [
        'userSelected' => 'selectUser',
        'refreshUserList' => 'refreshUsers'
    ];


    public function mount()
    {
        $this->users = User::all();
    }

    public function selectUser($userId)
    {
        \Log::info("selectUser");
        $this->selectedUserId = $userId;
        $this->dispatch('ProfileSelected', userId: $userId);
    }

    public function refreshUsers()
    {
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.user-list');
    }
}