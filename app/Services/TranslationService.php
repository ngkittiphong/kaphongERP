<?php

namespace App\Services;

use App\Models\TranslationKey;
use App\Models\TranslationValue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TranslationService
{
    private const CACHE_PREFIX = 'translations';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Get translation for a key and locale
     */
    public function get(string $key, string $locale = null, string $default = null): string
    {
        // If no locale specified, check session first
        if ($locale === null && request()->hasSession()) {
            $sessionLocale = request()->session()->get('locale', 'en');
            $supportedLocales = ['en', 'th'];
            if (in_array($sessionLocale, $supportedLocales)) {
                $locale = $sessionLocale;
                app()->setLocale($locale);
            } else {
                $locale = app()->getLocale();
            }
        } else {
            $locale = $locale ?: app()->getLocale();
        }
        
        $cacheKey = $this->getCacheKey($key, $locale);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($key, $locale, $default) {
            $translationKey = TranslationKey::active()
                ->where('key', $key)
                ->first();

            if (!$translationKey) {
                return $default ?: $key;
            }

            $value = $translationKey->getValueForLocale($locale);
            
            if ($value === null) {
                // Fallback to English if translation not found
                if ($locale !== 'en') {
                    $value = $translationKey->getValueForLocale('en');
                }
                
                return $value ?: $default ?: $key;
            }

            return $value;
        });
    }

    /**
     * Get all translations for a locale
     */
    public function getAllForLocale(string $locale): array
    {
        $cacheKey = $this->getCacheKey('all', $locale);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($locale) {
            return TranslationValue::active()
                ->byLocale($locale)
                ->with('translationKey')
                ->get()
                ->pluck('value', 'translationKey.key')
                ->toArray();
        });
    }

    /**
     * Get translations by group for a locale
     */
    public function getByGroup(string $group, string $locale): array
    {
        $cacheKey = $this->getCacheKey("group_{$group}", $locale);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($group, $locale) {
            return TranslationValue::active()
                ->byLocale($locale)
                ->whereHas('translationKey', function ($query) use ($group) {
                    $query->active()->byGroup($group);
                })
                ->with('translationKey')
                ->get()
                ->pluck('value', 'translationKey.key')
                ->toArray();
        });
    }

    /**
     * Clear translation cache
     */
    public function clearCache(string $key = null, string $locale = null): void
    {
        if ($key && $locale) {
            Cache::forget($this->getCacheKey($key, $locale));
        } else {
            // Clear all translation cache
            $pattern = self::CACHE_PREFIX . '*';
            Cache::flush(); // In production, you might want to use a more specific cache clearing method
        }
    }

    /**
     * Add or update a translation
     */
    public function set(string $key, string $locale, string $value, string $group = 'default', string $description = null): void
    {
        DB::transaction(function () use ($key, $locale, $value, $group, $description) {
            // Create or update translation key
            $translationKey = TranslationKey::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $group,
                    'description' => $description,
                    'is_active' => true
                ]
            );

            // Create or update translation value
            TranslationValue::updateOrCreate(
                [
                    'translation_key_id' => $translationKey->id,
                    'locale' => $locale
                ],
                [
                    'value' => $value,
                    'is_active' => true
                ]
            );

            // Clear cache for this key
            $this->clearCache($key, $locale);
        });
    }

    /**
     * Get available locales
     */
    public function getAvailableLocales(): array
    {
        return TranslationValue::active()
            ->distinct()
            ->pluck('locale')
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Generate cache key
     */
    private function getCacheKey(string $key, string $locale): string
    {
        return self::CACHE_PREFIX . ":{$locale}:{$key}";
    }
}
