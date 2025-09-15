<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransferSlip extends Model
{
    protected $fillable = [
        'user_request_id',
        'user_receive_id',
        'transfer_slip_number',
        'company_name',
        'company_address',
        'tax_id',
        'tel',
        'date_request',
        'user_request_name',
        'deliver_name',
        'date_receive',
        'user_receive_name',
        'warehouse_origin_id',
        'warehouse_origin_name',
        'warehouse_destination_id',
        'warehouse_destination_name',
        'total_quantity',
        'transfer_slip_status_id',
        'description',
        'note',
    ];

    protected $casts = [
        'date_request' => 'datetime',
        'date_receive' => 'datetime',
    ];

    /**
     * Get the user who requested the transfer.
     */
    public function userRequest(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_request_id');
    }

    /**
     * Get the user who received the transfer.
     */
    public function userReceive(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_receive_id');
    }

    /**
     * Get the origin warehouse.
     */
    public function warehouseOrigin(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_origin_id');
    }

    /**
     * Get the destination warehouse.
     */
    public function warehouseDestination(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_destination_id');
    }

    /**
     * Get the transfer slip status.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TransferSlipStatus::class, 'transfer_slip_status_id');
    }

    /**
     * Get the transfer slip details.
     */
    public function transferSlipDetails(): HasMany
    {
        return $this->hasMany(TransferSlipDetail::class);
    }

    /**
     * Get the inventories associated with this transfer slip.
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Generate a unique transfer slip number.
     */
    public static function generateTransferSlipNumber(): string
    {
        $prefix = 'TS';
        $date = now()->format('Ymd');
        $lastTransferSlip = self::where('transfer_slip_number', 'like', $prefix . $date . '%')
            ->orderBy('transfer_slip_number', 'desc')
            ->first();

        if ($lastTransferSlip) {
            $lastNumber = (int) substr($lastTransferSlip->transfer_slip_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate the total cost of the transfer slip.
     */
    public function getTotalCostAttribute(): float
    {
        return $this->transferSlipDetails()->sum('cost_total');
    }

    /**
     * Check if the transfer slip is completed.
     */
    public function isCompleted(): bool
    {
        return $this->date_receive !== null;
    }
}
