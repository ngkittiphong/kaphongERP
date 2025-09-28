<?php

if (!function_exists('__t')) {
    /**
     * Get translation for a key and locale
     *
     * @param string $key Translation key
     * @param string|null $default Default value if translation not found
     * @param string|null $locale Locale code (defaults to current locale)
     * @return string
     */
    function __t(string $key, ?string $default = null, ?string $locale = null): string
    {
        // If no locale specified, check session and set app locale
        if ($locale === null && request()->hasSession()) {
            $sessionLocale = request()->session()->get('locale', 'en');
            $supportedLocales = ['en', 'th'];
            if (in_array($sessionLocale, $supportedLocales)) {
                app()->setLocale($sessionLocale);
            }
        }
        
        return app(\App\Services\TranslationService::class)->get($key, $locale, $default);
    }
}
