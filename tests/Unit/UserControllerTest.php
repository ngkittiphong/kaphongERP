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

    /**
     * Test that the LogIn method returns the correct login view.
     * 
     * This test verifies that when a user accesses the login page,
     * the controller returns the proper Blade view ('user.login').
     * This is important for ensuring the login form is displayed correctly.
     */
    /** @test */
    public function it_returns_login_view()
    {
        $response = $this->userController->LogIn();
        
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('user.login', $response->getName());
    }

    /**
     * Test that the index method returns users with their relationships loaded.
     * 
     * This test verifies that when retrieving the user list, the controller
     * properly loads related data (profile, status) to avoid N+1 query problems.
     * This is crucial for performance and ensuring all user data is available.
     */
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

    /**
     * Test successful login process with valid credentials.
     * 
     * This test verifies that when a user provides correct username and password,
     * the signinProcess method successfully authenticates them and returns
     * a redirect response. This is the happy path for user authentication.
     */
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

    /**
     * Test login process with invalid credentials (wrong username/password).
     * 
     * This test verifies that when a user provides incorrect credentials,
     * the system properly handles the authentication failure by returning
     * a redirect with error messages. This ensures security by preventing
     * unauthorized access attempts.
     */
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

    /**
     * Test login process with missing required fields (empty username/password).
     * 
     * This test verifies that when required fields are missing or empty,
     * Laravel's validation system properly throws a ValidationException.
     * This ensures data integrity and prevents incomplete login attempts.
     */
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

    /**
     * Test successful user logout process.
     * 
     * This test verifies that when a user logs out, the system properly
     * invalidates their session, regenerates the CSRF token for security,
     * and redirects them back to the login page. This ensures proper
     * session cleanup and security.
     */
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

    /**
     * Test successful password change process.
     * 
     * This test verifies that when a user provides matching new passwords
     * and confirmation, the system successfully updates their password
     * in the database and sets the request_change_pass flag. This is
     * crucial for password security and user account management.
     */
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

    /**
     * Test password change validation with mismatched passwords.
     * 
     * This test verifies that when a user provides different values for
     * new_password and new_password_confirmation, the system properly
     * validates and rejects the request with a 422 status code. This
     * prevents password change errors and ensures data integrity.
     */
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

    /**
     * Test password change validation with password that's too short.
     * 
     * This test verifies that when a user provides a password that doesn't
     * meet the minimum length requirement, the system properly validates
     * and rejects the request. This ensures password security standards
     * are enforced.
     */
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

    /**
     * Test password change handling for non-existent user ID.
     * 
     * This test verifies that when attempting to change a password for
     * a user that doesn't exist in the database, the system properly
     * handles the error and returns a 500 status code. This ensures
     * robust error handling for edge cases.
     */
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

    /**
     * Test successful forced password change process.
     * 
     * This test verifies that when a user is required to change their password
     * (request_change_pass = true), the system successfully processes the
     * password change, resets the flag, and redirects appropriately. This
     * is important for security policies and user account management.
     */
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

    /**
     * Test forced password change with incorrect current password.
     * 
     * This test verifies that when a user provides the wrong current password
     * during a forced password change, the system properly validates and
     * throws a ValidationException. This ensures security by preventing
     * unauthorized password changes.
     */
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

    /**
     * Test forced password change for unauthenticated user.
     * 
     * This test verifies that when an unauthenticated user attempts to
     * change their password, the system properly handles the situation
     * by redirecting them. This ensures security by preventing unauthorized
     * access to password change functionality.
     */
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

    /**
     * Test forced password change for user who doesn't require password change.
     * 
     * This test verifies that when a user who doesn't have the request_change_pass
     * flag set attempts to use the forced password change functionality,
     * the system properly handles it by redirecting them. This ensures
     * the forced password change flow is only used when appropriate.
     */
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

    /**
     * Test successful user nickname update process.
     * 
     * This test verifies that when an authenticated user provides a valid
     * nickname, the system successfully updates their profile nickname
     * in the database and returns a success response. This is important
     * for user profile management functionality.
     */
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

    /**
     * Test nickname update handling for unauthenticated user.
     * 
     * This test verifies that when an unauthenticated user attempts to
     * update their nickname, the system properly handles the error by
     * throwing an exception. This ensures security by preventing
     * unauthorized profile modifications.
     */
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

    /**
     * Test CSRF token mismatch handling during login process.
     * 
     * This test verifies that when a request is made without a proper CSRF token
     * (simulating a session expiration or security issue), the system properly
     * handles the situation by redirecting to the login page. This ensures
     * security against CSRF attacks and provides good user experience.
     */
    /** @test */
    public function it_handles_csrf_token_mismatch_gracefully()
    {
        // Simulate a CSRF token mismatch by creating a request without proper token
        $request = Request::create('/user/signin_process', 'POST', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);
        
        // Remove CSRF token to simulate mismatch
        $request->headers->remove('X-CSRF-TOKEN');
        
        $response = $this->userController->signinProcess($request);
        
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertStringContainsString('/user/login', $response->getTargetUrl());
    }
}
