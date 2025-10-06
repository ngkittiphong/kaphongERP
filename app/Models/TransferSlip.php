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
     * Format: TF + YYYY + MM + DD + NNNN
     * Where NNNN is 4-digit sequence number for that specific date
     * Automatically handles duplicates by incrementing the sequence number
     */
    public static function generateTransferSlipNumber(int $maxAttempts = 10): string
    {
        $prefix = 'TF';
        $year = now()->format('Y'); // 4-digit year (e.g., 2025)
        $month = now()->format('m'); // 2-digit month (e.g., 01, 12)
        $day = now()->format('d'); // 2-digit day (e.g., 01, 31)
        $datePattern = $year . $month . $day; // YYYYMMDD
        
        // Find the last transfer slip for this specific date
        $lastTransferSlip = self::where('transfer_slip_number', 'like', $prefix . $datePattern . '%')
            ->orderBy('transfer_slip_number', 'desc')
            ->first();

        if ($lastTransferSlip) {
            // Extract the last 4 digits from the existing transfer_slip_number
            $lastNumber = (int) substr($lastTransferSlip->transfer_slip_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            // First transfer slip for this date
            $newNumber = 1;
        }

        // Try to generate a unique transfer slip number
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            // Ensure the sequence number is 4 digits with leading zeros
            $sequenceNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            $candidate = $prefix . $datePattern . $sequenceNumber; // e.g., TF202501150001
            
            // Check if this transfer slip number already exists
            if (!self::where('transfer_slip_number', $candidate)->exists()) {
                \Log::debug('Generated unique transfer slip number', [
                    'number' => $candidate,
                    'attempt' => $attempt + 1,
                    'date_pattern' => $datePattern
                ]);
                return $candidate;
            }
            
            // If it exists, increment and try again
            $newNumber++;
            \Log::warning('Transfer slip number collision detected, retrying', [
                'collision_number' => $candidate,
                'attempt' => $attempt + 1,
                'next_number' => $newNumber
            ]);
        }
        
        // If we've exhausted all attempts, use timestamp-based fallback
        $fallbackNumber = str_pad(now()->timestamp % 10000, 4, '0', STR_PAD_LEFT);
        $fallbackCandidate = $prefix . $datePattern . $fallbackNumber;
        
        \Log::error('Failed to generate unique transfer slip number after max attempts', [
            'max_attempts' => $maxAttempts,
            'fallback_number' => $fallbackCandidate,
            'date_pattern' => $datePattern
        ]);
        
        return $fallbackCandidate;
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
