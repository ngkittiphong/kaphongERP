<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Livewire\User\UserProfile;
use App\Models\User;
use App\Models\UserProfile as UserProfileModel;
use App\Models\UserStatus;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

class UserLivewireTest extends TestCase
{
    use RefreshDatabase;

    protected $userStatus;
    protected $userType;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->userStatus = UserStatus::create([
            'name' => 'Active',
            'sign' => 'active',
            'color' => 'green'
        ]);
        
        $this->userType = UserType::create([
            'name' => 'Admin',
            'sign' => 'admin',
            'color' => 'blue'
        ]);

        $this->user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
            'request_change_pass' => false,
        ]);

        UserProfileModel::create([
            'user_id' => $this->user->id,
            'fullname_th' => 'ทดสอบ',
            'fullname_en' => 'Test User',
            'nickname' => 'Test',
            'description' => 'Test user profile',
        ]);
    }

    /** @test */
    public function user_profile_component_can_be_rendered()
    {
        Livewire::test(UserProfile::class)
            ->assertStatus(200);
    }

    /** @test */
    public function user_profile_component_loads_user_data()
    {
        Livewire::test(UserProfile::class, ['user' => $this->user])
            ->assertSet('user.id', $this->user->id)
            ->assertSet('user.username', 'testuser')
            ->assertSet('user.email', 'test@example.com');
    }

    /** @test */
    public function user_profile_component_can_open_change_password_modal()
    {
        Livewire::test(UserProfile::class, ['user' => $this->user])
            ->call('openChangePasswordModal')
            ->assertSet('showChangePasswordModal', true);
    }

    /** @test */
    public function user_profile_component_can_close_change_password_modal()
    {
        Livewire::test(UserProfile::class, ['user' => $this->user])
            ->set('showChangePasswordModal', true)
            ->call('closeChangePasswordModal')
            ->assertSet('showChangePasswordModal', false)
            ->assertSet('new_password', null)
            ->assertSet('new_password_confirmation', null);
    }

    /** @test */
    public function user_profile_component_validates_password_change()
    {
        Livewire::test(UserProfile::class, ['user' => $this->user])
            ->set('new_password', 'short')
            ->set('new_password_confirmation', 'different')
            ->call('changePassword')
            ->assertHasErrors(['new_password', 'new_password_confirmation']);
    }

    /** @test */
    public function user_profile_component_can_change_password_successfully()
    {
        // Mock the UserController response
        $this->mock(\App\Http\Controllers\UserController::class, function ($mock) {
            $mock->shouldReceive('changePassword')
                ->once()
                ->andReturn(response()->json(['message' => 'Password changed successfully!'], 200));
        });

        Livewire::test(UserProfile::class, ['user' => $this->user])
            ->set('new_password', 'newpassword123')
            ->set('new_password_confirmation', 'newpassword123')
            ->call('changePassword')
            ->assertSet('showChangePasswordModal', false)
            ->assertSet('new_password', null)
            ->assertSet('new_password_confirmation', null);
    }

    /** @test */
    public function user_profile_component_handles_password_change_errors()
    {
        // Mock the UserController response with error
        $this->mock(\App\Http\Controllers\UserController::class, function ($mock) {
            $mock->shouldReceive('changePassword')
                ->once()
                ->andReturn(response()->json([
                    'error' => 'Failed to change password',
                    'errors' => ['new_password' => ['Password is too short']]
                ], 422));
        });

        Livewire::test(UserProfile::class, ['user' => $this->user])
            ->set('new_password', 'short')
            ->set('new_password_confirmation', 'short')
            ->call('changePassword')
            ->assertHasErrors(['new_password']);
    }

    /** @test */
    public function user_profile_component_can_display_edit_profile_form()
    {
        Livewire::test(UserProfile::class, ['user' => $this->user])
            ->call('displayEditProfileForm')
            ->assertSet('showEditProfileForm', true);
    }

    /** @test */
    public function user_profile_component_can_close_edit_profile_form()
    {
        Livewire::test(UserProfile::class, ['user' => $this->user])
            ->set('showEditProfileForm', true)
            ->call('closeEditProfileForm')
            ->assertSet('showEditProfileForm', false);
    }


    /** @test */
    public function user_profile_component_can_handle_missing_user()
    {
        Livewire::test(UserProfile::class, ['user' => null])
            ->call('changePassword')
            ->assertHasErrors(['form']);
    }

    /** @test */
    public function user_profile_component_loads_user_relationships()
    {
        $component = Livewire::test(UserProfile::class, ['user' => $this->user]);
        
        $this->assertTrue($component->instance()->user->relationLoaded('profile'));
        $this->assertTrue($component->instance()->user->relationLoaded('status'));
        $this->assertTrue($component->instance()->user->relationLoaded('type'));
    }

}
