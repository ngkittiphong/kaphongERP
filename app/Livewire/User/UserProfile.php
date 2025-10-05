<?php

namespace App\Livewire\User;

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
    public $showChangePasswordModal = false;
    public $username, $email, $password, $password_confirmation, $user_type_id, $user_status_id;
    public $new_password, $new_password_confirmation;
    public $nickname, $card_id_no, $fullname_th, $fullname_en, $prefix_en, $prefix_th, $birth_date, $description;
    public $avatar;
    public $userTypes = [];
    public $userStatuses = [];

    protected $listeners = [
        'ProfileSelected' => 'loadProfile',
        'showAddUserForm' => 'displayAddUserForm',
        'showEditProfileForm' => 'displayEditProfileForm',
        'showChangePasswordModal' => 'displayChangePasswordModal',
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
        
        // Always populate core user properties first
        if ($this->user) {
            $this->username = $this->user->username;
            $this->email = $this->user->email;
            $this->user_type_id = $this->user->user_type_id;
            $this->user_status_id = $this->user->user_status_id;
        }
        
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
            $this->birth_date = $this->user->profile->birth_date ? 
                $this->user->profile->birth_date->format('Y-m-d') : '';
            $this->description = $this->user->profile->description;
        }

        //$this->dispatch('refreshComponent');
        $this->showAddUserForm = false; // Hide form when selecting an existing user
    }

    public function displayAddUserForm()
    {
        \Log::info("Livewire Event Received: showAddUserForm"); // Debugging log
        $this->showAddUserForm = true;
        $this->reset([
            'username','email','password','password_confirmation',
            'user_type_id','user_status_id','nickname','card_id_no',
            'fullname_th','fullname_en','prefix_en','prefix_th',
            'birth_date','description'
        ]);
        $this->resetErrorBag();
        $this->user = null;
        $this->user_type_id = 1;
        $this->user_status_id = 1;
        
        // Set default prefix values
        $this->prefix_th = 'นาย';
        $this->prefix_en = 'Mr.';
        
        $this->dispatch('addUser');
    }

    public function displayChangePasswordModal()
    {
        \Log::info("Livewire Event Received: showChangePasswordModal");
        $this->showChangePasswordModal = true;
        $this->reset(['new_password', 'new_password_confirmation']);
        $this->resetErrorBag();
    }

    public function closeChangePasswordModal()
    {
        $this->showChangePasswordModal = false;
        $this->reset(['new_password', 'new_password_confirmation']);
        $this->resetErrorBag();
    }

    public function changePassword()
    {

        if (!$this->user) {
            throw new \Exception('User not found');
        }

        // Create a Request instance with the password data
        $request = new Request([
            'new_password' => $this->new_password,
            'new_password_confirmation' => $this->new_password_confirmation,
        ]);

        // Instantiate the controller and call its changePassword method
        $controller = new UserController();
        $response = $controller->changePassword($request, $this->user->id);
        
        if ($response->status() === 200) {
            session()->flash('message', 'Password changed successfully!');
            \Log::info("✅ Password changed successfully for user ID: {$this->user->id}");
            $this->showChangePasswordModal = false;
            $this->reset(['new_password', 'new_password_confirmation']);
        } else {
            // Get error message from response
            $errorData = json_decode($response->getContent(), true);
            $errorMessage = $errorData['error'] ?? 'Failed to change password.';
            
            // Add errors to form validation
            if (isset($errorData['errors'])) {
                foreach ($errorData['errors'] as $field => $messages) {
                    foreach ($messages as $message) {
                        $this->addError($field, $message);
                    }
                }
                \Log::error("❌ Validation errors: " . json_encode($errorData['errors']));
            } else {
                $this->addError('form', $errorMessage);
                \Log::error("❌ Form error: " . $errorMessage);
            }
        }
            

    }

    public function displayEditProfileForm()
    {
        \Log::info("Livewire Event Received: showEditProfileForm"); // Debugging log
        $this->showEditProfileForm = true;
        //$this->dispatch('refreshComponent');
        $this->dispatch('profileUpdated');
    }

    public function saveUserAndProfile()
    {   
        $this->resetValidation();

        // Create a Request instance with the validated data
        $requestData = [
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
                'avatar'               => $this->avatar,
                ];

        \Log::debug("About to create user with data:", $requestData);
        $request = new Request($requestData);

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
            $resultData = json_decode($response->getContent(), true);
            $resultMessage = $resultData['message'] ?? 'Failed to create user & profile.';
            $resultError = $resultData['error'] ?? '';
            
            \Log::error("❌ User creation failed with status: " . $response->status());
            \Log::error("❌ Response content: " . $response->getContent());
            
            // Add errors to form validation
            if (isset($resultData['errors'])) {
                // Handle validation errors
                foreach ($resultData['errors'] as $field => $messages) {
                    foreach ($messages as $message) {
                        $this->addError($field, $message);
                    }
                }
                \Log::error("❌ Validation errors: " . json_encode($resultData['errors']));
            } elseif (isset($errorData['error'])) {
                // Handle single error message
                $this->addError('form', $resultMessage);
                \Log::error("❌ Form error: " . $resultMessage);
            } else {
                // Handle generic error
                session()->flash('error', $resultMessage);
                \Log::error("❌ Failed to create user & profile: " . $resultMessage);
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
                'avatar'               => $this->avatar,
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
        $this->dispatch('refreshUserList');
    }

    private function resetForm()
    {
        $this->reset([
            'username','email','password','password_confirmation',
            'user_type_id','user_status_id','nickname','card_id_no',
            'fullname_th','fullname_en','prefix_en','prefix_th',
            'birth_date','description','avatar'
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
        return view('livewire.user.user-profile');
    }
}