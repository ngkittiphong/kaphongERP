<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockChecker extends Model
{
    protected $fillable = [
        'checker_number',
        'user_check_id',
        'warehouse_id',
        'date_create',
    ];

    protected $casts = [
        'date_create' => 'datetime',
    ];

    /**
     * Get the user who performed the stock check.
     */
    public function userCheck(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_check_id');
    }

    /**
     * Get the warehouse where the stock check was performed.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the stock checker details.
     */
    public function stockCheckerDetails(): HasMany
    {
        return $this->hasMany(StockCheckerDetail::class);
    }

    /**
     * Generate a unique checker number.
     * Format: CS + YYYY + MM + DD + NNNN
     * Where NNNN is 4-digit sequence number for that specific date
     * Automatically handles duplicates by incrementing the sequence number
     */
    public static function generateCheckerNumber(int $maxAttempts = 10): string
    {
        $prefix = 'CS';
        $year = now()->format('Y'); // 4-digit year (e.g., 2025)
        $month = now()->format('m'); // 2-digit month (e.g., 01, 12)
        $day = now()->format('d'); // 2-digit day (e.g., 01, 31)
        $datePattern = $year . $month . $day; // YYYYMMDD
        
        // Find the last stock checker for this specific date
        $lastChecker = self::where('checker_number', 'like', $prefix . $datePattern . '%')
            ->orderBy('checker_number', 'desc')
            ->first();

        if ($lastChecker) {
            // Extract the last 4 digits from the existing checker_number
            $lastNumber = (int) substr($lastChecker->checker_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            // First stock checker for this date
            $newNumber = 1;
        }

        // Try to generate a unique checker number
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            // Ensure the sequence number is 4 digits with leading zeros
            $sequenceNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            $candidate = $prefix . $datePattern . $sequenceNumber; // e.g., CS202501150001
            
            // Check if this checker number already exists
            if (!self::where('checker_number', $candidate)->exists()) {
                \Log::debug('Generated unique checker number', [
                    'number' => $candidate,
                    'attempt' => $attempt + 1,
                    'date_pattern' => $datePattern
                ]);
                return $candidate;
            }
            
            // If it exists, increment and try again
            $newNumber++;
            \Log::warning('Checker number collision detected, retrying', [
                'collision_number' => $candidate,
                'attempt' => $attempt + 1,
                'next_number' => $newNumber
            ]);
        }
        
        // If we've exhausted all attempts, use timestamp-based fallback
        $fallbackNumber = str_pad(now()->timestamp % 10000, 4, '0', STR_PAD_LEFT);
        $fallbackCandidate = $prefix . $datePattern . $fallbackNumber;
        
        \Log::error('Failed to generate unique checker number after max attempts', [
            'max_attempts' => $maxAttempts,
            'fallback_number' => $fallbackCandidate,
            'date_pattern' => $datePattern
        ]);
        
        return $fallbackCandidate;
    }
}
