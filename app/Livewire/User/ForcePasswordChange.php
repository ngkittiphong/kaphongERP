<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\ValidationRulesService;

class ForcePasswordChange extends Component
{
    public $new_password = '';
    public $new_password_confirmation = '';
    public $current_password = '';

    protected $rules = [];

    protected $messages = [];

    public function mount()
    {
        // Load rules and messages from centralized service
        $this->rules = ValidationRulesService::getForcePasswordChangeRules();
        $this->messages = ValidationRulesService::getForcePasswordChangeMessages();
        
        // Ensure user is authenticated and needs to change password
        if (!Auth::check() || !Auth::user()->request_change_pass) {
            return redirect()->route('login');
        }
    }

    public function changePassword()
    {
        $this->validate();

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        try {
            // Update password
            $user->password = Hash::make($this->new_password);
            
            // Reset the request_change_pass flag
            $user->request_change_pass = false;
            
            $user->save();

            // Log the successful password change
            \Log::info('Forced password change completed', [
                'user_id' => $user->id,
                'username' => $user->username
            ]);

            session()->flash('success', 'Password changed successfully! You can now access the system.');
            
            // Redirect to home page after successful password change
            return redirect('/');

        } catch (\Exception $e) {
            \Log::error('Error during forced password change: ' . $e->getMessage());
            $this->addError('form', 'An error occurred while changing your password. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.user.force-password-change')
            ->layout('layout.master_layout');
    }
}
