<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Product extends Model
{


    // Disable default timestamps since we use `date_create`
    public $timestamps = false;

    // Table name is `products` by convention, so no need to override

    // Mass-assignable attributes
    protected $fillable = [
        'product_no',
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
        'date_create'
    ];

    // Casts for special data types
    protected $casts = [
        'buy_price' => 'float',
        'sale_price' => 'float',
        'minimum_quantity' => 'integer',
        'maximum_quantity' => 'integer',
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

    /**
     * Get the warehouse products for this product.
     */
    public function warehouseProducts(): HasMany
    {
        return $this->hasMany(WarehouseProduct::class);
    }

    /**
     * Get the warehouses that contain this product through the pivot table.
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'warehouses_products')
                    ->withPivot(['balance', 'avr_buy_price', 'avr_sale_price', 'avr_remain_price'])
                    ->withTimestamps();
    }

    /**
     * Generate a unique product number
     * Format: PD + YYYY + MM + ID (same logic as UserProfile)
     */
    public static function generateProductNumber(int $maxAttempts = 5): string
    {
        $year = now()->format('Y'); // 4-digit year (e.g., 2025)
        $month = now()->format('m'); // 2-digit month (e.g., 01, 12)
        
        // Get the next available ID from the database
        $nextId = static::max('id') + 1;
        
        // Ensure the ID is 5 digits with leading zeros
        $productId = str_pad($nextId, 5, '0', STR_PAD_LEFT);
        
        // Generate the final product number
        $candidate = "PD{$year}{$month}{$productId}"; // e.g., PD20250100001
        
        // Double-check uniqueness (shouldn't be necessary with database IDs, but safety first)
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            if (!static::where('product_no', $candidate)->exists()) {
                return $candidate;
            }
            // If somehow it exists, increment and try again
            $nextId++;
            $productId = str_pad($nextId, 5, '0', STR_PAD_LEFT);
            $candidate = "PD{$year}{$month}{$productId}";
        }
        
        // Fallback: use timestamp-based ID if all else fails
        $fallbackId = str_pad(now()->timestamp % 100000, 5, '0', STR_PAD_LEFT);
        return "PD{$year}{$month}{$fallbackId}";
    }

    /**
     * Boot method to auto-generate product_no before creating (same logic as UserProfile)
     */
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (!empty($product->product_no)) {
                return;
            }

            $product->product_no = static::generateProductNumber();
        });
    }
}







