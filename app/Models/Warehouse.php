<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    protected $fillable = [
        'branch_id',
        'user_create_id',
        'main_warehouse',
        'name',
        'date_create',
        'warehouse_status_id',
        'avr_remain_price',
    ];

    protected $casts = [
        'date_create' => 'datetime',
        'main_warehouse' => 'boolean',
        'avr_remain_price' => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(WarehouseStatus::class, 'warehouse_status_id');
    }

    public function userCreate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    /**
     * Get the check stock reports for this warehouse.
     */
    public function checkStockReports(): HasMany
    {
        return $this->hasMany(CheckStockReport::class);
    }

    /**
     * Get the warehouse products for this warehouse.
     */
    public function warehouseProducts(): HasMany
    {
        return $this->hasMany(WarehouseProduct::class);
    }

    /**
     * Get the products that belong to this warehouse through the pivot table.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'warehouses_products')
                    ->withPivot(['balance', 'avr_buy_price', 'avr_sale_price', 'avr_remain_price'])
                    ->withTimestamps();
    }
}
