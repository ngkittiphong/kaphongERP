<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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

    /**
     * Generate warehouse ID based on the sequential number within the branch
     * Format: WH + YYYY + MM + BB + WWW
     * Where BB is 2-digit branch_id and WWW is 3-digit sequential warehouse number in that branch
     */
    public function getWarehouseIdAttribute(): string
    {
        $year = $this->created_at ? $this->created_at->format('Y') : now()->format('Y');
        $month = $this->created_at ? $this->created_at->format('m') : now()->format('m');
        $branchIdPadded = str_pad($this->branch_id, 2, '0', STR_PAD_LEFT);
        
        // Count warehouses in this branch (including this one) ordered by creation
        $warehouseSequence = static::where('branch_id', $this->branch_id)
            ->where('created_at', '<=', $this->created_at)
            ->count();
        
        $warehouseIdPadded = str_pad($warehouseSequence, 3, '0', STR_PAD_LEFT);
        
        return "WH{$year}{$month}{$branchIdPadded}{$warehouseIdPadded}";
    }

    /**
     * Static method to generate warehouse ID for a given warehouse
     */
    public static function generateWarehouseId(int $warehouseId, int $branchId, $createdAt = null): string
    {
        $date = $createdAt ? \Carbon\Carbon::parse($createdAt) : now();
        $year = $date->format('Y');
        $month = $date->format('m');
        $branchIdPadded = str_pad($branchId, 2, '0', STR_PAD_LEFT);
        
        // Count warehouses in this branch up to the given date
        $warehouseSequence = static::where('branch_id', $branchId)
            ->where('created_at', '<=', $date)
            ->count();
        
        $warehouseIdPadded = str_pad($warehouseSequence, 3, '0', STR_PAD_LEFT);
        
        return "WH{$year}{$month}{$branchIdPadded}{$warehouseIdPadded}";
    }

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

    /**
     * Scope a query to only include active warehouses (exclude status 0).
     */
    public function scopeActive($query)
    {
        return $query->where('warehouse_status_id', '!=', 0);
    }
}
