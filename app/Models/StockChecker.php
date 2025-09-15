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
     */
    public static function generateCheckerNumber(): string
    {
        $prefix = 'SC';
        $date = now()->format('Ymd');
        $lastChecker = self::where('checker_number', 'like', $prefix . $date . '%')
            ->orderBy('checker_number', 'desc')
            ->first();

        if ($lastChecker) {
            $lastNumber = (int) substr($lastChecker->checker_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
