<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockCheckerDetail extends Model
{
    protected $fillable = [
        'stock_checker_id',
        'product_id',
        'date_check',
        'count_stock',
        'remain_stock',
    ];

    protected $casts = [
        'date_check' => 'datetime',
    ];

    /**
     * Get the stock checker that owns this detail.
     */
    public function stockChecker(): BelongsTo
    {
        return $this->belongsTo(StockChecker::class);
    }

    /**
     * Get the product being checked.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the difference between counted stock and remaining stock.
     */
    public function getStockDifferenceAttribute(): int
    {
        return $this->count_stock - $this->remain_stock;
    }

    /**
     * Check if there's a stock discrepancy.
     */
    public function hasDiscrepancy(): bool
    {
        return $this->count_stock !== $this->remain_stock;
    }
}
