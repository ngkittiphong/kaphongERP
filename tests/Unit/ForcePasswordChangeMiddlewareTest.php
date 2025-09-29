<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Middleware\ForcePasswordChange;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route as RouteFacade;
use Mockery;

class ForcePasswordChangeMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected $middleware;
    protected $userStatus;
    protected $userType;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->middleware = new ForcePasswordChange();
        
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
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_allows_unauthenticated_users_to_pass()
    {
        $request = Request::create('/some-route');
        $next = function ($req) {
            return new Response('OK');
        };

        // Mock unauthenticated user
        Auth::shouldReceive('check')->andReturn(false);

        $response = $this->middleware->handle($request, $next);
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function it_allows_authenticated_users_without_password_change_requirement()
    {
        $request = Request::create('/some-route');
        $next = function ($req) {
            return new Response('OK');
        };

        // Mock authenticated user who doesn't need to change password
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($this->user);

        $response = $this->middleware->handle($request, $next);
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function it_redirects_user_requiring_password_change_to_change_password_page()
    {
        // Set user to require password change
        $this->user->update(['request_change_pass' => true]);

        $request = Request::create('/some-protected-route');
        $next = function ($req) {
            return new Response('OK');
        };

        // Mock authenticated user who needs to change password
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($this->user);

        $response = $this->middleware->handle($request, $next);
        
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('user.change-password'), $response->getTargetUrl());
        $this->assertTrue($response->getSession()->has('warning'));
    }

    /** @test */
    public function it_allows_access_to_password_change_route()
    {
        // Set user to require password change
        $this->user->update(['request_change_pass' => true]);

        $request = Request::create('/user/change-password');
        
        // Mock the route with name
        $route = Mockery::mock(Route::class);
        $route->shouldReceive('getName')->andReturn('user.change-password');
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $next = function ($req) {
            return new Response('OK');
        };

        // Mock authenticated user who needs to change password
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($this->user);

        $response = $this->middleware->handle($request, $next);
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function it_allows_access_to_logout_route()
    {
        // Set user to require password change
        $this->user->update(['request_change_pass' => true]);

        $request = Request::create('/user/signOut');
        
        // Mock the route with name
        $route = Mockery::mock(Route::class);
        $route->shouldReceive('getName')->andReturn('user.signOut');
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $next = function ($req) {
            return new Response('OK');
        };

        // Mock authenticated user who needs to change password
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($this->user);

        $response = $this->middleware->handle($request, $next);
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function it_allows_access_to_login_route()
    {
        // Set user to require password change
        $this->user->update(['request_change_pass' => true]);

        $request = Request::create('/user/login');
        
        // Mock the route with name
        $route = Mockery::mock(Route::class);
        $route->shouldReceive('getName')->andReturn('login');
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $next = function ($req) {
            return new Response('OK');
        };

        // Mock authenticated user who needs to change password
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($this->user);

        $response = $this->middleware->handle($request, $next);
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function it_redirects_when_route_name_is_null()
    {
        // Set user to require password change
        $this->user->update(['request_change_pass' => true]);

        $request = Request::create('/some-route');
        
        // Mock the route with null name
        $route = Mockery::mock(Route::class);
        $route->shouldReceive('getName')->andReturn(null);
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $next = function ($req) {
            return new Response('OK');
        };

        // Mock authenticated user who needs to change password
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($this->user);

        $response = $this->middleware->handle($request, $next);
        
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('user.change-password'), $response->getTargetUrl());
    }

    /** @test */
    public function it_redirects_when_route_is_null()
    {
        // Set user to require password change
        $this->user->update(['request_change_pass' => true]);

        $request = Request::create('/some-route');
        
        // Mock null route
        $request->setRouteResolver(function () {
            return null;
        });

        $next = function ($req) {
            return new Response('OK');
        };

        // Mock authenticated user who needs to change password
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($this->user);

        $response = $this->middleware->handle($request, $next);
        
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('user.change-password'), $response->getTargetUrl());
    }

    /** @test */
    public function it_includes_warning_message_in_redirect()
    {
        // Set user to require password change
        $this->user->update(['request_change_pass' => true]);

        $request = Request::create('/some-protected-route');
        $next = function ($req) {
            return new Response('OK');
        };

        // Mock authenticated user who needs to change password
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($this->user);

        $response = $this->middleware->handle($request, $next);
        
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertTrue($response->getSession()->has('warning'));
        $this->assertEquals('You must change your password before continuing.', $response->getSession()->get('warning'));
    }

    /** @test */
    public function it_handles_multiple_allowed_routes_correctly()
    {
        // Set user to require password change
        $this->user->update(['request_change_pass' => true]);

        $allowedRoutes = ['user.change-password', 'user.signOut', 'login'];
        
        foreach ($allowedRoutes as $routeName) {
            $request = Request::create('/some-route');
            
            // Mock the route with name
            $route = Mockery::mock(Route::class);
            $route->shouldReceive('getName')->andReturn($routeName);
            $request->setRouteResolver(function () use ($route) {
                return $route;
            });

            $next = function ($req) {
                return new Response('OK');
            };

            // Mock authenticated user who needs to change password
            Auth::shouldReceive('check')->andReturn(true);
            Auth::shouldReceive('user')->andReturn($this->user);

            $response = $this->middleware->handle($request, $next);
            
            $this->assertInstanceOf(Response::class, $response);
            $this->assertEquals('OK', $response->getContent());
        }
    }

    /** @test */
    public function it_blocks_access_to_protected_routes_when_password_change_required()
    {
        // Set user to require password change
        $this->user->update(['request_change_pass' => true]);

        $protectedRoutes = ['menu.menu_product', 'menu.menu_warehouse', 'inventory.index'];
        
        foreach ($protectedRoutes as $routeName) {
            $request = Request::create('/some-route');
            
            // Mock the route with name
            $route = Mockery::mock(Route::class);
            $route->shouldReceive('getName')->andReturn($routeName);
            $request->setRouteResolver(function () use ($route) {
                return $route;
            });

            $next = function ($req) {
                return new Response('OK');
            };

            // Mock authenticated user who needs to change password
            Auth::shouldReceive('check')->andReturn(true);
            Auth::shouldReceive('user')->andReturn($this->user);

            $response = $this->middleware->handle($request, $next);
            
            $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
            $this->assertEquals(route('user.change-password'), $response->getTargetUrl());
        }
    }
}
