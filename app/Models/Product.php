<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Product extends Model
{


    // Disable default timestamps since we use `date_create`
    public $timestamps = false;

    // Table name is `products` by convention, so no need to override

    // Mass-assignable attributes
    protected $fillable = [
        'product_type_id',
        'product_group_id',
        'product_status_id',
        'sku_number',
        'serial_number',
        'name',
        'product_cover_img',
        'unit_name',
        'buy_price',
        'buy_vat_id',
        'buy_withholding_id',
        'buy_description',
        'sale_price',
        'sale_vat_id',
        'sale_withholding_id',
        'sale_description',
        'minimum_quantity',
        'maximum_quantity',
        'date_create',
        'is_active'
    ];

    // Casts for special data types
    protected $casts = [
        'buy_price' => 'float',
        'sale_price' => 'float',
        'minimum_quantity' => 'integer',
        'maximum_quantity' => 'integer',
        'is_active' => 'boolean',
        'date_create' => 'datetime'
    ];

    /**
     * Relationships
     */

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(ProductGroup::class, 'product_group_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ProductStatus::class, 'product_status_id');
    }

    public function buyVat(): BelongsTo
    {
        return $this->belongsTo(Vat::class, 'buy_vat_id');
    }

    public function saleVat(): BelongsTo
    {
        return $this->belongsTo(Vat::class, 'sale_vat_id');
    }

    public function buyWithholding(): BelongsTo
    {
        return $this->belongsTo(Withholding::class, 'buy_withholding_id');
    }

    public function saleWithholding(): BelongsTo
    {
        return $this->belongsTo(Withholding::class, 'sale_withholding_id');
    }

    public function subUnits(): HasMany
    {
        return $this->hasMany(ProductSubUnit::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get the check stock details for this product.
     */
    public function checkStockDetails(): HasMany
    {
        return $this->hasMany(CheckStockDetail::class);
    }
}







