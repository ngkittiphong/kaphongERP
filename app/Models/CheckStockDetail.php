<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckStockDetail extends Model
{
    protected $fillable = [
        'user_check_id',
        'product_id',
        'check_stock_report_id',
        'product_scan_num',
        'datetime_scan',
    ];

    protected $casts = [
        'datetime_scan' => 'datetime',
        'product_scan_num' => 'integer',
    ];

    /**
     * Get the user who performed the scan.
     */
    public function userCheck(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_check_id');
    }

    /**
     * Get the product being scanned.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the check stock report that owns this detail.
     */
    public function checkStockReport(): BelongsTo
    {
        return $this->belongsTo(CheckStockReport::class);
    }

    /**
     * Get the scan date in a formatted string.
     */
    public function getFormattedScanDateAttribute(): string
    {
        return $this->datetime_scan->format('Y-m-d H:i:s');
    }

    /**
     * Get the scan date in a human-readable format.
     */
    public function getHumanReadableScanDateAttribute(): string
    {
        return $this->datetime_scan->diffForHumans();
    }
}
