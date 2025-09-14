<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'warehouse_code',
        'name_th',
        'name_en',
        'address_th',
        'address_en',
        'phone_number',
        'email',
        'is_active',
        'is_main_warehouse',
        'description',
        'contact_name',
        'contact_email',
        'contact_mobile',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
