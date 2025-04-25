<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // If user is not authenticated, redirect to login using our custom path
            return redirect('/user/login');
        }
        \Log::info('Request details:', [
            'method' => $request->method(),
            'url' => $request->url(),
            'path' => $request->path(),
            'query' => $request->query(),
            'headers' => $request->headers->all(),
            'ip' => $request->ip()
        ]);
        return $next($request);
    }
} 