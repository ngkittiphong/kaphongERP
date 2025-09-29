<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserStatus;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

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
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $userData = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
            'request_change_pass' => false,
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('testuser', $user->username);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertFalse($user->request_change_pass);
        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function it_has_fillable_fields()
    {
        $user = new User();
        $expectedFillable = [
            'username',
            'email',
            'password',
            'profile_id',
            'user_status_id',
            'user_type_id',
            'request_change_pass',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    /** @test */
    public function it_hides_sensitive_fields()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
        ]);

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    /** @test */
    public function it_belongs_to_user_status()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
        ]);

        $this->assertInstanceOf(UserStatus::class, $user->status);
        $this->assertEquals($this->userStatus->id, $user->status->id);
        $this->assertEquals('Active', $user->status->name);
    }

    /** @test */
    public function it_belongs_to_user_type()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
        ]);

        $this->assertInstanceOf(UserType::class, $user->type);
        $this->assertEquals($this->userType->id, $user->type->id);
        $this->assertEquals('Admin', $user->type->name);
    }

    /** @test */
    public function it_has_one_profile()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
        ]);

        $profile = UserProfile::create([
            'user_id' => $user->id,
            'fullname_th' => 'ทดสอบ',
            'fullname_en' => 'Test User',
            'nickname' => 'Test',
            'description' => 'Test user profile',
        ]);

        $this->assertInstanceOf(UserProfile::class, $user->profile);
        $this->assertEquals($profile->id, $user->profile->id);
        $this->assertEquals('ทดสอบ', $user->profile->fullname_th);
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
        ]);

        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertNull(User::find($user->id));
        $this->assertNotNull(User::withTrashed()->find($user->id));
    }

    /** @test */
    public function it_can_restore_soft_deleted_user()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
        ]);

        $user->delete();
        $this->assertSoftDeleted('users', ['id' => $user->id]);

        $user->restore();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_can_update_request_change_pass_flag()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
            'request_change_pass' => false,
        ]);

        $this->assertFalse($user->request_change_pass);

        $user->update(['request_change_pass' => true]);
        $this->assertTrue((bool) $user->fresh()->request_change_pass);

        $user->update(['request_change_pass' => false]);
        $this->assertFalse((bool) $user->fresh()->request_change_pass);
    }

    /** @test */
    public function it_casts_timestamps_to_datetime()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $user->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->updated_at);
    }

    /** @test */
    public function it_can_verify_password()
    {
        $password = 'password123';
        $hashedPassword = Hash::make($password);

        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => $hashedPassword,
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
        ]);

        $this->assertTrue(Hash::check($password, $user->password));
        $this->assertFalse(Hash::check('wrongpassword', $user->password));
    }

    /** @test */
    public function it_can_scope_active_users()
    {
        // Create active user
        User::create([
            'username' => 'activeuser',
            'email' => 'active@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
        ]);

        // Create inactive user status
        $inactiveStatus = UserStatus::create([
            'name' => 'Inactive',
            'sign' => 'inactive',
            'color' => 'red'
        ]);

        // Create inactive user
        User::create([
            'username' => 'inactiveuser',
            'email' => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'user_status_id' => $inactiveStatus->id,
            'user_type_id' => $this->userType->id,
        ]);

        $activeUsers = User::whereHas('status', function ($query) {
            $query->where('name', 'Active');
        })->get();

        $this->assertCount(1, $activeUsers);
        $this->assertEquals('activeuser', $activeUsers->first()->username);
    }
}
