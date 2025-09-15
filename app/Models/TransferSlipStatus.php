<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransferSlipStatus extends Model
{
    protected $fillable = [
        'name',
        'sign',
        'color',
    ];

    /**
     * Get the transfer slips with this status.
     */
    public function transferSlips(): HasMany
    {
        return $this->hasMany(TransferSlip::class);
    }
}
