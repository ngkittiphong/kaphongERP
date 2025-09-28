<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TranslationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TranslationService::class, function ($app) {
            return new TranslationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load helper functions
        if (file_exists(app_path('helpers.php'))) {
            require_once app_path('helpers.php');
        }
        
        // Set locale from session - Laravel 11 compatible approach
        $this->app->afterResolving('request', function ($request) {
            if ($request->hasSession()) {
                $locale = $request->session()->get('locale', 'en');
                $supportedLocales = ['en', 'th'];
                if (in_array($locale, $supportedLocales)) {
                    $this->app->setLocale($locale);
                }
            }
        });
    }
}
