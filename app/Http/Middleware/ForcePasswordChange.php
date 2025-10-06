<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // User is not authenticated, redirect to login
            return redirect()->route('login')
                ->with('error', 'Please login to continue.');
        }

        $user = Auth::user();
        
        // Check if user needs to change password
        if ($user->request_change_pass) {
            // Set session timeout to 5 minutes for users who need to change password
            config(['session.lifetime' => 5]);
            
            // Check if session has expired
            $sessionLifetime = config('session.lifetime', 5);
            $sessionLastActivity = session('last_activity', now()->timestamp);
            $timeSinceLastActivity = now()->timestamp - ($sessionLifetime * 60); // Convert minutes to seconds
            
            if ($sessionLastActivity < $timeSinceLastActivity) {
                // Session expired, logout user and redirect to login
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                \Log::info('Session expired for user with force password change', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'session_lifetime' => $sessionLifetime,
                    'last_activity' => $sessionLastActivity,
                    'current_time' => now()->timestamp
                ]);
                
                return redirect()->route('login')
                    ->with('error', 'Session expired. Please login again and change your password within 5 minutes.');
            }
            
            // Update last activity timestamp
            session(['last_activity' => now()->timestamp]);
            
            // Allow access to password change route and logout
            $allowedRoutes = [
                'user.change-password',
                'user.signOut',
                'login'
            ];
            
            $currentRoute = $request->route() ? $request->route()->getName() : null;
            
            // If not on an allowed route, redirect to password change
            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('user.change-password')
                    ->with('warning', 'You must change your password within 5 minutes or your session will expire.');
            }
        } else {
            // User doesn't need to change password, check for general session expiration
            $this->checkGeneralSessionExpiration($request);
        }

        return $next($request);
    }

    /**
     * Check for general session expiration (for users who don't need to change password)
     */
    private function checkGeneralSessionExpiration(Request $request)
    {
        $sessionLifetime = config('session.lifetime', 120); // Default 2 hours
        $sessionLastActivity = session('last_activity', now()->timestamp);
        $timeSinceLastActivity = now()->timestamp - ($sessionLifetime * 60); // Convert minutes to seconds
        
        if ($sessionLastActivity < $timeSinceLastActivity) {
            // Session expired, logout user and redirect to login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            \Log::info('General session expired', [
                'session_lifetime' => $sessionLifetime,
                'last_activity' => $sessionLastActivity,
                'current_time' => now()->timestamp
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }
        
        // Update last activity timestamp
        session(['last_activity' => now()->timestamp]);
    }
}
