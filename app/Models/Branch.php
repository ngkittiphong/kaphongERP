<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_code',
        'name_th',
        'name_en',
        'address_th',
        'address_en',
        'bill_address_th',
        'bill_address_en',
        'post_code',
        'phone_country_code',
        'phone_number',
        'fax',
        'website',
        'email',
        'is_active',
        'is_head_office',
        'latitude',
        'longitude',
        'contact_name',
        'contact_email',
        'contact_mobile',
    ];

    /**
     * Boot the model and add event listeners
     */
    protected static function booted()
    {
        static::creating(function ($branch) {
            // Auto-generate branch_code if not provided
            if (empty($branch->branch_code)) {
                $branch->branch_code = static::generateBranchCode();
                \Log::debug('Auto-generated branch code during creation', [
                    'branch_code' => $branch->branch_code,
                    'branch_name' => $branch->name_en ?? $branch->name_th ?? 'Unknown'
                ]);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    /**
     * Generate a unique branch code.
     * Format: B + YYYY + MM + DD + BB
     * Where BB is 2-digit branch ID (padded with leading zeros)
     * Automatically handles duplicates by incrementing the branch ID
     */
    public static function generateBranchCode(int $maxAttempts = 10): string
    {
        $prefix = 'B';
        $year = now()->format('Y'); // 4-digit year (e.g., 2025)
        $month = now()->format('m'); // 2-digit month (e.g., 01, 12)
        $day = now()->format('d'); // 2-digit day (e.g., 01, 31)
        $datePattern = $year . $month . $day; // YYYYMMDD
        
        // Get the next available ID from the database
        $nextId = static::max('id') + 1;
        
        // Try to generate a unique branch code
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            // Ensure the branch ID is 2 digits with leading zeros
            $branchId = str_pad($nextId, 2, '0', STR_PAD_LEFT);
            $candidate = $prefix . $datePattern . $branchId; // e.g., B20250115001
            
            // Check if this branch code already exists
            if (!static::where('branch_code', $candidate)->exists()) {
                \Log::debug('Generated unique branch code', [
                    'code' => $candidate,
                    'attempt' => $attempt + 1,
                    'date_pattern' => $datePattern,
                    'branch_id' => $nextId
                ]);
                return $candidate;
            }
            
            // If it exists, increment and try again
            $nextId++;
            \Log::warning('Branch code collision detected, retrying', [
                'collision_code' => $candidate,
                'attempt' => $attempt + 1,
                'next_id' => $nextId
            ]);
        }
        
        // If we've exhausted all attempts, use timestamp-based fallback
        $fallbackId = str_pad(now()->timestamp % 100, 2, '0', STR_PAD_LEFT);
        $fallbackCandidate = $prefix . $datePattern . $fallbackId;
        
        \Log::error('Failed to generate unique branch code after max attempts', [
            'max_attempts' => $maxAttempts,
            'fallback_code' => $fallbackCandidate,
            'date_pattern' => $datePattern
        ]);
        
        return $fallbackCandidate;
    }
}
