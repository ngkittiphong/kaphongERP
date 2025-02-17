<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserList extends Component
{
    public $users;
    public $selectedUserId;
    public $search = ''; // 🔹 Store search input

    protected $listeners = [
        'userSelected' => 'selectUser',
        'refreshUserList' => 'refreshUsers'
    ];


    public function mount()
    {
        \Log::info("UserList Component Mounted");
        $this->refreshUsers();
    }

    public function updatedSearch()
    {
        $this->refreshUsers(); // 🔹 Trigger filtering when search updates
    }

    public function searchUsers()
    {
        \Log::info("Search Triggered: " . $this->search);
        $this->refreshUsers();
    }

    public function refreshUsers()
    {
        \Log::info("Refreshing User List with search: {$this->search}");
        $this->users = User::where('username', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%")
            ->orderBy('username')
            ->get();
    }

    public function selectUser($userId)
    {
        \Log::info("selectUser");
        $this->selectedUserId = $userId;
        $this->dispatch('ProfileSelected', userId: $userId);
    }

    public function render()
    {
        return view('livewire.user-list');
    }
}