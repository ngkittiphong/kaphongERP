<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserStatus;
use App\Models\UserType;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ForcePasswordChange;
use App\Livewire\User\UserProfile as UserProfileComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

/**
 * Comprehensive Test Suite for User Module
 * 
 * This test suite covers all aspects of the user module including:
 * - User Model (relationships, validation, soft deletes)
 * - UserController (authentication, password management, profile updates)
 * - ForcePasswordChange Middleware (route protection, redirects)
 * - User Livewire Components (UI interactions, form validation)
 */
class UserModuleTestSuite extends TestCase
{
    use RefreshDatabase;

    protected $userStatus;
    protected $userType;
    protected $user;
    protected $userProfile;

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

        $this->userProfile = UserProfile::create([
            'user_id' => $this->user->id,
            'fullname_th' => 'ทดสอบ',
            'fullname_en' => 'Test User',
            'nickname' => 'Test',
            'description' => 'Test user profile',
        ]);
    }

    /** @test */
    public function user_module_integration_test()
    {
        // Test complete user workflow
        
        // 1. User can be created with all relationships
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertEquals('testuser', $this->user->username);
        $this->assertInstanceOf(UserStatus::class, $this->user->status);
        $this->assertInstanceOf(UserType::class, $this->user->type);
        $this->assertInstanceOf(UserProfile::class, $this->user->profile);

        // 2. User can authenticate
        Auth::login($this->user);
        $this->assertTrue(Auth::check());
        $this->assertEquals($this->user->id, Auth::id());

        // 3. UserController can retrieve user data
        $controller = new UserController();
        $users = $controller->index();
        $this->assertCount(1, $users);
        $this->assertEquals('testuser', $users->first()->username);

        // 4. User can change password
        $request = new \Illuminate\Http\Request([
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
            'request_change_pass' => true,
        ]);

        $response = $controller->changePassword($request, $this->user->id);
        $this->assertEquals(200, $response->getStatusCode());

        // 5. Password change updates request_change_pass flag
        $updatedUser = User::find($this->user->id);
        $this->assertTrue($updatedUser->request_change_pass);

        // 6. Middleware redirects user requiring password change
        $middleware = new ForcePasswordChange();
        $request = new \Illuminate\Http\Request();
        $request->setRouteResolver(function () {
            $route = new \Illuminate\Routing\Route(['GET'], '/test', []);
            $route->name('test.route');
            return $route;
        });

        $next = function ($req) {
            return new \Illuminate\Http\Response('OK');
        };

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($updatedUser);

        $response = $middleware->handle($request, $next);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);

        // 7. Livewire components work correctly
        Livewire::test(UserProfileComponent::class, ['user' => $this->user])
            ->assertSet('user.id', $this->user->id)
            ->call('openChangePasswordModal')
            ->assertSet('showChangePasswordModal', true);

        // 8. Force password change component works
        // Note: ForcePasswordChange Livewire component has been removed
        // The functionality is now handled by the traditional Blade template
    }

    /** @test */
    public function user_module_security_test()
    {
        // Test security aspects
        
        // 1. Passwords are hashed
        $this->assertNotEquals('password123', $this->user->password);
        $this->assertTrue(Hash::check('password123', $this->user->password));

        // 2. Sensitive fields are hidden
        $userArray = $this->user->toArray();
        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);

        // 3. Soft deletes work
        $this->user->delete();
        $this->assertSoftDeleted('users', ['id' => $this->user->id]);
        $this->assertNull(User::find($this->user->id));

        // 4. User can be restored
        $this->user->restore();
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function user_module_validation_test()
    {
        // Test validation rules
        
        // 1. User creation validation
        $this->expectException(\Illuminate\Database\QueryException::class);
        User::create([
            'email' => 'test@example.com',
            // Missing required fields
        ]);

        // 2. Password change validation
        $controller = new UserController();
        $request = new \Illuminate\Http\Request([
            'new_password' => 'short',
            'new_password_confirmation' => 'different',
        ]);

        $response = $controller->changePassword($request, $this->user->id);
        $this->assertEquals(422, $response->getStatusCode());

        // 3. Livewire component validation
        Livewire::test(UserProfileComponent::class, ['user' => $this->user])
            ->set('new_password', 'short')
            ->set('new_password_confirmation', 'different')
            ->call('changePassword')
            ->assertHasErrors(['new_password', 'new_password_confirmation']);
    }

    /** @test */
    public function user_module_performance_test()
    {
        // Test performance aspects
        
        // 1. User index loads relationships efficiently
        $startTime = microtime(true);
        
        $controller = new UserController();
        $users = $controller->index();
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        
        $this->assertLessThan(1.0, $executionTime); // Should complete in less than 1 second
        $this->assertTrue($users->first()->relationLoaded('profile'));
        $this->assertTrue($users->first()->relationLoaded('status'));

        // 2. Middleware doesn't cause performance issues
        $middleware = new ForcePasswordChange();
        $request = new \Illuminate\Http\Request();
        $request->setRouteResolver(function () {
            $route = new \Illuminate\Routing\Route(['GET'], '/test', []);
            $route->name('user.change-password');
            return $route;
        });

        $next = function ($req) {
            return new \Illuminate\Http\Response('OK');
        };

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($this->user);

        $startTime = microtime(true);
        $response = $middleware->handle($request, $next);
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        
        $this->assertLessThan(0.1, $executionTime); // Should complete in less than 0.1 seconds
        $this->assertInstanceOf(\Illuminate\Http\Response::class, $response);
    }

    /** @test */
    public function user_module_error_handling_test()
    {
        // Test error handling
        
        // 1. Controller handles missing user gracefully
        $controller = new UserController();
        $request = new \Illuminate\Http\Request([
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response = $controller->changePassword($request, 999); // Non-existent user
        $this->assertEquals(500, $response->getStatusCode());

        // 2. Livewire component handles missing user
        Livewire::test(UserProfileComponent::class, ['user' => null])
            ->call('changePassword')
            ->assertHasErrors(['form']);

        // 3. Middleware handles null route gracefully
        $middleware = new ForcePasswordChange();
        $request = new \Illuminate\Http\Request();
        $request->setRouteResolver(function () {
            return null;
        });

        $next = function ($req) {
            return new \Illuminate\Http\Response('OK');
        };

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($this->user);

        $response = $middleware->handle($request, $next);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }
}
