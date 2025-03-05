<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

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

    public function render()
    {
        // // 🔹 Query your users, 10 per page
        $users = User::where('username', 'like', "%{$this->search}%")
        ->orWhere('email', 'like', "%{$this->search}%")
        ->orderBy('username')
        ->paginate(10);
        //$users = User::paginate(10);  

        // Return the Blade view with paginated $users
        return view('livewire.user-list', compact('users'));
    }
}