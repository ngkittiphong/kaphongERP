<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferSlipDetail extends Model
{
    protected $fillable = [
        'transfer_slip_id',
        'product_id',
        'product_name',
        'product_description',
        'quantity',
        'unit_name',
        'cost_per_unit',
        'cost_total',
    ];

    protected $casts = [
        'cost_per_unit' => 'decimal:2',
        'cost_total' => 'decimal:2',
    ];

    /**
     * Get the transfer slip that owns this detail.
     */
    public function transferSlip(): BelongsTo
    {
        return $this->belongsTo(TransferSlip::class);
    }

    /**
     * Get the product being transferred.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the total cost for this detail.
     */
    public function calculateTotalCost(): void
    {
        $this->cost_total = $this->quantity * $this->cost_per_unit;
    }

    /**
     * Boot method to automatically calculate total cost.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($detail) {
            $detail->calculateTotalCost();
        });
    }
}
