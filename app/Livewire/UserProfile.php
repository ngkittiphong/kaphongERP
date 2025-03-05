<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;


class UserProfile extends Component
{
    public $user;
    public $showAddUserForm = false;
    public $showEditProfileForm = false;
    public $username, $email, $password, $password_confirmation, $user_type_id, $user_status_id;
    public $nickname, $card_id_no, $fullname_th, $fullname_en, $prefix_en, $prefix_th, $birth_date, $description;
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
        \Log::info("loadUserProfile");
        $this->showEditProfileForm = false;
        $this->user = User::with('profile')->find($userId) ?? null;
        if ($this->user && !$this->user->profile) {
            \Log::warning("User {$userId} has no profile");
            $this->user->profile = null;
        }else{
            $this->nickname = $this->user->profile->nickname;
            $this->card_id_no = $this->user->profile->card_id_no;
            $this->fullname_th = $this->user->profile->fullname_th;
            $this->fullname_en = $this->user->profile->fullname_en;
            $this->prefix_en = $this->user->profile->prefix_en;
            $this->prefix_th = $this->user->profile->prefix_th;
            $this->birth_date = $this->user->profile->birth_date;
            $this->description = $this->user->profile->description;
        }

        //$this->dispatch('refreshComponent');
        $this->showAddUserForm = false; // Hide form when selecting an existing user
    }

    public function displayAddUserForm()
    {
        \Log::info("Livewire Event Received: showUserForm"); // Debugging log
        $this->showAddUserForm = true;
        $this->dispatch('refreshComponent');
        $this->user = null;
    }

    public function displayEditProfileForm()
    {
        \Log::info("Livewire Event Received: showEditProfileForm"); // Debugging log
        $this->showEditProfileForm = true;
        $this->dispatch('refreshComponent');
    }

    public function saveUserAndProfile()
    {   

        // Create a Request instance with the validated data
        $request = new Request([
                'username'             => $this->username,
                'email'                => $this->email,
                'password'             => $this->password,
                'password_confirmation'=> $this->password_confirmation,
                'user_type_id'         => $this->user_type_id,
                'user_status_id'       => $this->user_status_id,
    
                // Profile fields
                'nickname'             => $this->nickname,
                'card_id_no'           => $this->card_id_no,
                'fullname_th'          => $this->fullname_th,
                'fullname_en'          => $this->fullname_en,
                'prefix_en'            => $this->prefix_en,
                'prefix_th'            => $this->prefix_th,
                'birth_date'           => $this->birth_date,
                'description'          => $this->description,
                ]);

        // Instantiate the controller and call its store method
        $controller = new UserController();
        $response = $controller->store($request);
       
        if ($response->status() === 201) {
            session()->flash('message', 'User & Profile created successfully!');
            \Log::info("✅ Profile created/updated successfully!");
            $this->showAddUserForm = false;
            $this->dispatch('userCreated', [
                'message' => 'User create was successful!'
            ]);
            // Optionally refresh the user list
            $this->dispatch('refreshUserList');
            $this->dispatch('refreshComponent');
            // Reset form fields
            $this->resetForm();
            
        } else {
            // Get error message from response
            $errorData = json_decode($response->getContent(), true);
            $errorMessage = $errorData['message'] ?? 'Failed to create user & profile.';
            
            // Add errors to form validation
            if (isset($errorData['error'])) {
                $this->addError('form', $errorMessage);
                \Log::error("❌ Form validation error: " . $errorMessage);
            } else {
                session()->flash('error', $errorMessage);
                \Log::error("❌ Failed to create user & profile: " . $errorMessage);
            }
        }
    }

    public function updateUserAndProfile()
    {   
        // Create a Request instance with the validated data
        $request = new Request([
                'username'             => $this->username,
                'email'                => $this->email,
                'password'             => $this->password,
                'password_confirmation'=> $this->password_confirmation,
                'user_type_id'         => $this->user_type_id,
                'user_status_id'       => $this->user_status_id,
    
                // Profile fields
                'nickname'             => $this->nickname,
                'card_id_no'           => $this->card_id_no,
                'fullname_th'          => $this->fullname_th,
                'fullname_en'          => $this->fullname_en,
                'prefix_en'            => $this->prefix_en,
                'prefix_th'            => $this->prefix_th,
                'birth_date'           => $this->birth_date,
                'description'          => $this->description,
                ]);

        // Instantiate the controller and call its update method
        $controller = new UserController();
        $response = $controller->update($request, $this->user->id);
               
        if ($response->status() === 200) {
            session()->flash('message', 'User & Profile updated successfully!');
            \Log::info("✅ Profile updated successfully!");
            $this->showEditProfileForm = false;
            $this->dispatch('refreshComponent');
        } else {
            // Get error message from response
            $errorData = json_decode($response->getContent(), true);
           //$errorMessage = $errorData['message'] ?? 'Failed to update user & profile.';
            $errorMessage = $errorData['error'] ?? 'Failed to update user & profile.';
            // Add errors to form validation
            if (isset($errorData['error'])) {
                $this->addError('form', $errorMessage);
                \Log::error("❌ Form validation error: " . $errorMessage);
            } else {
                session()->flash('error', $errorMessage);
                \Log::error("❌ Failed to update user & profile: " . $errorMessage);
            }
        }
    }

    private function resetForm()
    {
        $this->reset([
            'username','email','password','password_confirmation',
            'user_type_id','user_status_id','nickname','card_id_no',
            'fullname_th','fullname_en','prefix_en','prefix_th',
            'birth_date','description'
        ]);
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