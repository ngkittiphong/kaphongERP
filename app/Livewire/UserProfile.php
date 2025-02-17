<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserStatus;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;

class UserProfile extends Component
{
    public $user;
    public $showAddUsetForm = false;
    public $showEditProfileForm = false;
    public $username, $email, $password, $password_confirmation, $user_type_id, $user_status_id;
    public $nickname, $id_no, $fullname_th, $fullname_en, $prefix_en, $prefix_th, $birth_date, $description;
    public $avatar;
    public $userTypes = [];
    public $userStatuses = [];

    protected $listeners = [
        'ProfileSelected' => 'loadProfile',
        'showAddUserForm' => 'displayAddUserForm',
        'showEditProfileForm' => 'displayEditProfileForm',
        'refreshComponent' => '$refresh',
        'createUserProfile' => 'createUserProfile',
        'deleteUser' => 'deleteUser'
    ];

    public function mount()
    {
        \Log::info("UserProfile Component Mounted");
        $this->userTypes = UserType::all();
        $this->userStatuses = UserStatus::all();
    }

    public function loadProfile($userId)
    {
        \Log::info("loadProfile");
        $this->showEditProfileForm = false;
        $this->user = User::with('profile')->find($userId) ?? null;
        if ($this->user && !$this->user->profile) {
            \Log::warning("User {$userId} has no profile");
            $this->user->profile = null;
        }else{
            $this->nickname = $this->user->profile->nickname;
            $this->id_no = $this->user->profile->id_no;
            $this->fullname_th = $this->user->profile->fullname_th;
            $this->fullname_en = $this->user->profile->fullname_en;
            $this->prefix_en = $this->user->profile->prefix_en;
            $this->prefix_th = $this->user->profile->prefix_th;
            $this->birth_date = $this->user->profile->birth_date;
            $this->description = $this->user->profile->description;
        }

        $this->dispatch('refreshComponent');
        $this->showAddUsetForm = false; // Hide form when selecting an existing user
    }

    public function displayAddUserForm()
    {
        \Log::info("Livewire Event Received: showUserForm"); // Debugging log
        $this->showAddUsetForm = true;
        $this->dispatch('refreshComponent');
        $this->user = null;
    }

    public function displayEditProfileForm()
    {
        \Log::info("Livewire Event Received: showEditProfileForm"); // Debugging log
        $this->showEditProfileForm = true;
        $this->dispatch('refreshComponent');
    }

    public function saveUser()
    {
        // Validate using UserRequest rules
        $validated = $this->validate((new UserRequest())->rules());

        User::create([
            'username' => $this->username,  // Include username
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'user_type_id' => $this->user_type_id,
            'user_status_id' => $this->user_status_id,
        ]);
    
        session()->flash('message', 'User added successfully!');
        $this->dispatch('refreshUserList'); // Refresh list
        $this->showAddUsetForm = false; // Hide form
    }

    public function saveUserProfile()
    {
        if (!$this->user) {
            \Log::error("❌ User is null in saveUserProfile()");
            session()->flash('error', 'User is not set. Please check and try again.');
            return;
        }

        try {
            // Ensure profile relationship exists before calling create()
            if (!$this->user->profile) {
                \Log::error("❌ Profile relationship is null for user ID: " . $this->user->id);
                // Create profile for the user
                $this->user->profile()->create([
                    'nickname' => $this->nickname,
                    'id_no' => $this->id_no, 
                    'fullname_th' => $this->fullname_th,
                    'fullname_en' => $this->fullname_en,
                    'prefix_en' => $this->prefix_en,
                    'prefix_th' => $this->prefix_th,
                    'birth_date' => $this->birth_date,
                    'description' => $this->description,
                    'avatar' => $this->avatar,
                ]);
            } else {
                // Update existing profile
                $this->user->profile->update([
                    'nickname' => $this->nickname,
                    'id_no' => $this->id_no,
                    'fullname_th' => $this->fullname_th, 
                    'fullname_en' => $this->fullname_en,
                    'prefix_en' => $this->prefix_en,
                    'prefix_th' => $this->prefix_th,
                    'birth_date' => $this->birth_date,
                    'description' => $this->description,
                    'avatar' => $this->avatar,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Error creating/updating profile: " . $e->getMessage());
            session()->flash('error', 'An error occurred while saving the profile. Please try again.');
            return;
        }

        session()->flash('message', 'Profile created successfully!');
        \Log::info("✅ Profile created/updated successfully!");
        $this->showEditProfileForm = false; // Hide form
        $this->dispatch('refreshComponent'); // Refresh list
    }

    public function deleteUser()
    {
        if (!$this->user) {
            \Log::error("❌ User is null in deleteUser()");
            session()->flash('error', 'User not found. Please check and try again.');
            return;
        }

        try {
            // Delete profile first if exists
            if ($this->user->profile) {
                $this->user->profile->delete();
            }

            // Delete user
            $this->user->delete();

            session()->flash('message', 'User deleted successfully!');
            \Log::info("✅ User deleted successfully!");
            
            $this->dispatch('refreshUserList'); // Refresh list
            $this->dispatch('refreshComponent'); // Refresh list
            $this->user = null; // Clear selected user

        } catch (\Exception $e) {
            \Log::error("Error deleting user: " . $e->getMessage());
            session()->flash('error', 'An error occurred while deleting the user. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}