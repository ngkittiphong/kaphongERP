<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TranslationKey extends Model
{
    protected $fillable = [
        'key',
        'group',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all translation values for this key
     */
    public function values(): HasMany
    {
        return $this->hasMany(TranslationValue::class);
    }

    /**
     * Get translation value for a specific locale
     */
    public function getValueForLocale(string $locale): ?string
    {
        $value = $this->values()
            ->where('locale', $locale)
            ->where('is_active', true)
            ->first();

        return $value ? $value->value : null;
    }

    /**
     * Scope to get active translation keys
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get keys by group
     */
    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}
