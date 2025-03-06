<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserList extends Component
{
    use WithPagination; // 🔹 Required for Livewire pagination


    public $selectedUserId;
    public $search = ''; // 🔹 Store search input

    protected $listeners = [
        'userSelected' => 'selectUser',
    ];


    public function mount()
    {
        \Log::info("UserList Component Mounted");
        $this->loadUsers();
    }

     // If you filter by search, reset to page 1 whenever search changes
     public function updatingSearch()
     {
         $this->resetPage();
     }

    public function searchUsers()
    {
        \Log::info("Search Triggered: " . $this->search);
        $this->resetPage();
    }

    public function selectUser($userId)
    {
        \Log::info("selectUser");
        $this->selectedUserId = $userId;
        $this->dispatch('ProfileSelected', userId: $userId);
    }

    public function loadUsers()
    {
        $this->users = User::select(['id', 'email', 'username'])
            ->orderBy('username')
            ->get(); // Load all users at once
    }

    public function render()
    {
        return view('livewire.user-list', [
            'users' => $this->users, // 🔹 Pass $users to the Blade view
        ]);
    }
}