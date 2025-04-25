<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AutoLogin
{
    public function handle($request, Closure $next)
    {
        if (
            app()->environment('local') &&
            env('AUTO_LOGIN_ENABLED', false) &&
            ! Auth::check()
        ) {
            Auth::login(
                User::firstWhere('username', config('app.auto_login_user', 'admin1'))
            );
        }

        return $next($request);
    }
}