<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserStatus;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mockery;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $userController;
    protected $userStatus;
    protected $userType;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userController = new UserController();
        
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

        UserProfile::create([
            'user_id' => $this->user->id,
            'fullname_th' => 'ทดสอบ',
            'fullname_en' => 'Test User',
            'nickname' => 'Test',
            'description' => 'Test user profile',
        ]);
    }

    /** @test */
    public function it_returns_login_view()
    {
        $response = $this->userController->LogIn();
        
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('user.login', $response->getName());
    }

    /** @test */
    public function it_returns_user_index_with_relationships()
    {
        $users = $this->userController->index();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $users);
        $this->assertCount(1, $users);
        
        $user = $users->first();
        $this->assertEquals('testuser', $user->username);
        $this->assertEquals('test@example.com', $user->email);
        
        // Check if relationships are loaded
        $this->assertTrue($user->relationLoaded('profile'));
        $this->assertTrue($user->relationLoaded('status'));
    }

    /** @test */
    public function it_validates_signin_process_with_valid_data()
    {
        $request = Request::create('/user/signin_process', 'POST', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $response = $this->userController->signinProcess($request);
        
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function it_handles_signin_process_with_invalid_credentials()
    {
        $request = Request::create('/user/signin_process', 'POST', [
            'username' => 'nonexistentuser',
            'password' => 'wrongpassword',
        ]);

        $response = $this->userController->signinProcess($request);
        
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertTrue($response->getSession()->has('errors'));
    }

    /** @test */
    public function it_validates_signin_process_with_missing_data()
    {
        $request = Request::create('/user/signin_process', 'POST', [
            'username' => '',
            'password' => '',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->userController->signinProcess($request);
    }

    /** @test */
    public function it_signs_out_user_successfully()
    {
        $request = Request::create('/user/signOut', 'GET');
        
        // Mock session
        $session = Mockery::mock(\Illuminate\Session\Store::class);
        $session->shouldReceive('invalidate')->once();
        $session->shouldReceive('regenerateToken')->once();
        $request->setLaravelSession($session);
        
        $response = $this->userController->signOut($request);
        
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertStringContainsString('/user/login', $response->getTargetUrl());
    }

    /** @test */
    public function it_changes_password_successfully()
    {
        $request = Request::create('/users/1/change-password', 'POST', [
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
            'request_change_pass' => true,
        ]);

        $response = $this->userController->changePassword($request, $this->user->id);
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Password changed successfully!', $responseData['message']);
        
        // Verify password was changed
        $updatedUser = User::find($this->user->id);
        $this->assertTrue(Hash::check('newpassword123', $updatedUser->password));
        $this->assertTrue((bool) $updatedUser->request_change_pass);
    }

    /** @test */
    public function it_validates_password_change_with_mismatched_passwords()
    {
        $request = Request::create('/users/1/change-password', 'POST', [
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'differentpassword',
            'request_change_pass' => false,
        ]);

        $response = $this->userController->changePassword($request, $this->user->id);
        
        $this->assertEquals(422, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('new_password_confirmation', $responseData['errors']);
    }

    /** @test */
    public function it_validates_password_change_with_short_password()
    {
        $request = Request::create('/users/1/change-password', 'POST', [
            'new_password' => '123',
            'new_password_confirmation' => '123',
            'request_change_pass' => false,
        ]);

        $response = $this->userController->changePassword($request, $this->user->id);
        
        $this->assertEquals(422, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('new_password', $responseData['errors']);
    }

    /** @test */
    public function it_handles_password_change_for_nonexistent_user()
    {
        $request = Request::create('/users/999/change-password', 'POST', [
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
            'request_change_pass' => false,
        ]);

        $response = $this->userController->changePassword($request, 999);
        
        $this->assertEquals(500, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
    }

    /** @test */
    public function it_handles_forced_password_change_successfully()
    {
        // Set user to require password change
        $this->user->update(['request_change_pass' => true]);
        
        // Mock authentication
        Auth::shouldReceive('user')->andReturn($this->user);
        Auth::shouldReceive('loginUsingId')->with($this->user->id)->once();

        $request = Request::create('/user/change-password', 'POST', [
            'current_password' => 'password123',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response = $this->userController->forceChangePassword($request);
        
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertStringContainsString('/', $response->getTargetUrl());
        
        // Verify password was changed and flag was reset
        $updatedUser = User::find($this->user->id);
        $this->assertTrue(Hash::check('newpassword123', $updatedUser->password));
        $this->assertFalse((bool) $updatedUser->request_change_pass);
    }

    /** @test */
    public function it_handles_forced_password_change_with_wrong_current_password()
    {
        // Set user to require password change
        $this->user->update(['request_change_pass' => true]);
        
        // Mock authentication
        Auth::shouldReceive('user')->andReturn($this->user);

        $request = Request::create('/user/change-password', 'POST', [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->userController->forceChangePassword($request);
    }

    /** @test */
    public function it_handles_forced_password_change_for_unauthenticated_user()
    {
        // Mock unauthenticated user
        Auth::shouldReceive('user')->andReturn(null);

        $request = Request::create('/user/change-password', 'POST', [
            'current_password' => 'password123',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response = $this->userController->forceChangePassword($request);
        
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertStringContainsString('/', $response->getTargetUrl());
    }

    /** @test */
    public function it_handles_forced_password_change_for_user_not_requiring_change()
    {
        // User doesn't require password change
        $this->user->update(['request_change_pass' => false]);
        
        // Mock authentication
        Auth::shouldReceive('user')->andReturn($this->user);

        $request = Request::create('/user/change-password', 'POST', [
            'current_password' => 'password123',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response = $this->userController->forceChangePassword($request);
        
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertStringContainsString('/', $response->getTargetUrl());
    }

    /** @test */
    public function it_updates_user_nickname_successfully()
    {
        $request = Request::create('/users/update-nickname', 'POST', [
            'nickname' => 'NewNickname',
        ]);

        // Mock authentication
        Auth::shouldReceive('user')->andReturn($this->user);

        $response = $this->userController->updateNickname($request);
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Nickname updated successfully', $responseData['message']);
        
        // Verify nickname was updated
        $updatedProfile = UserProfile::where('user_id', $this->user->id)->first();
        $this->assertEquals('NewNickname', $updatedProfile->nickname);
    }

    /** @test */
    public function it_handles_nickname_update_for_unauthenticated_user()
    {
        // Mock unauthenticated user
        Auth::shouldReceive('user')->andReturn(null);

        $request = Request::create('/users/update-nickname', 'POST', [
            'nickname' => 'NewNickname',
        ]);

        $this->expectException(\Error::class);
        $this->userController->updateNickname($request);
    }

    /** @test */
    public function it_validates_nickname_update_with_empty_nickname()
    {
        // Mock authentication
        Auth::shouldReceive('user')->andReturn($this->user);

        $request = Request::create('/users/update-nickname', 'POST', [
            'nickname' => '',
        ]);

        $response = $this->userController->updateNickname($request);
        
        $this->assertEquals(422, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('nickname', $responseData['errors']);
    }
}
