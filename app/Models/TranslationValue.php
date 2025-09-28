<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranslationValue extends Model
{
    protected $fillable = [
        'translation_key_id',
        'locale',
        'value',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the translation key that owns this value
     */
    public function translationKey(): BelongsTo
    {
        return $this->belongsTo(TranslationKey::class);
    }

    /**
     * Scope to get active translation values
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get values by locale
     */
    public function scopeByLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }
}
