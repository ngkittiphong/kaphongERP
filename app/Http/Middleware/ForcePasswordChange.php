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
        // Only check for authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user needs to change password
            if ($user->request_change_pass) {
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
                        ->with('warning', 'You must change your password before continuing.');
                }
            }
        }
        // If user is not authenticated, let the request continue (auth middleware will handle login redirect)

        return $next($request);
    }
}
