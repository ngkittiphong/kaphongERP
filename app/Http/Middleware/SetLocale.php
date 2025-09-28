<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session, default to 'en'
        $locale = $request->session()->get('locale', 'en');
        
        // Debug logging
        if (config('app.debug')) {
            \Log::info('SetLocale middleware DEBUG:', [
                'session_id' => $request->session()->getId(),
                'session_locale' => $request->session()->get('locale'),
                'requested_locale' => $locale,
                'current_app_locale' => app()->getLocale(),
                'session_data' => $request->session()->all()
            ]);
        }
        
        // Validate locale against supported locales
        $supportedLocales = ['en', 'th'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'en';
            // Set the default locale in session if it's invalid
            $request->session()->put('locale', $locale);
        }
        
        // Set the application locale
        app()->setLocale($locale);
        
        // Log for debugging (remove in production)
        if (config('app.debug')) {
            \Log::info('SetLocale middleware: Setting locale to ' . $locale . ' for session ' . $request->session()->getId());
        }
        
        return $next($request);
    }
}
