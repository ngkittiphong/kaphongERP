<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LanguageController
{
    /**
     * Switch language
     */
    public function switch(Request $request): JsonResponse|RedirectResponse
    {
        $locale = $request->input('locale');
        $supportedLocales = ['en', 'th'];
        
        // Log for debugging
        if (config('app.debug')) {
            \Log::info('Language switch request:', [
                'locale' => $locale,
                'session_id' => session()->getId(),
                'current_locale' => session('locale'),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);
        }
        
        if (!in_array($locale, $supportedLocales)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unsupported locale'], 400);
            }
            return redirect()->back()->with('error', 'Unsupported locale');
        }
        
        // Set locale in session
        session(['locale' => $locale]);
        
        // Log successful switch
        if (config('app.debug')) {
            \Log::info('Language switched successfully:', [
                'new_locale' => $locale,
                'session_id' => session()->getId()
            ]);
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'message' => 'Language switched successfully'
            ]);
        }
        
        return redirect()->back();
    }
    
    /**
     * Get current locale
     */
    public function getCurrent(): JsonResponse
    {
        return response()->json([
            'locale' => session('locale', 'en')
        ]);
    }
}
