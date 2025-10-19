<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserList extends Component
{
    public $selectedUserId;

    protected $listeners = [
        'userSelected' => 'selectUser',
        'refreshUserList' => 'loadUsers',
    ];

    public function mount()
    {
        \Log::info("UserList Component Mounted");
        $this->loadUsers();
    }

    public function selectUser($userId)
    {
        \Log::info("selectUser");
        $this->selectedUserId = $userId;
        $this->dispatch('ProfileSelected', userId: $userId);
        $this->dispatch('accessUserSelected', $userId);
    }

    public function loadUsers()
    {
        $this->users = User::select(['id', 'email', 'username'])
            ->orderBy('username')
            ->get(); // Load all users at once
        
            $this->dispatch('userListUpdated');
    }

    public function render()
    {
        return view('livewire.user.user-list', [
            'users' => $this->users, // 🔹 Pass $users to the Blade view
        ]);
    }
}
