<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserProfile extends Component
{
    public $user;

    protected $listeners = ['userSelected' => 'loadUser'];

    public function loadUser($userId)
    {
        $this->user = User::find($userId);
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}