<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CheckStockReport extends Model
{
    protected $fillable = [
        'user_create_id',
        'warehouse_id',
        'datetime_create',
        'closed',
    ];

    protected $casts = [
        'datetime_create' => 'datetime',
        'closed' => 'boolean',
    ];

    /**
     * Get the user who created this stock report.
     */
    public function userCreate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    /**
     * Get the warehouse where the stock check was performed.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the check stock details for this report.
     */
    public function checkStockDetails(): HasMany
    {
        return $this->hasMany(CheckStockDetail::class);
    }

    /**
     * Check if the report is closed.
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * Close the report.
     */
    public function close(): void
    {
        $this->update(['closed' => true]);
    }

    /**
     * Open the report.
     */
    public function open(): void
    {
        $this->update(['closed' => false]);
    }

    /**
     * Get the total number of products scanned in this report.
     */
    public function getTotalScannedAttribute(): int
    {
        return $this->checkStockDetails()->sum('product_scan_num');
    }

    /**
     * Get the number of unique products scanned in this report.
     */
    public function getUniqueProductsScannedAttribute(): int
    {
        return $this->checkStockDetails()->distinct('product_id')->count();
    }
}
