<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseProduct extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'warehouses_products';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'warehouse_id',
        'product_id',
        'balance',
        'avr_buy_price',
        'avr_sale_price',
        'avr_remain_price',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'balance' => 'integer',
        'avr_buy_price' => 'decimal:2',
        'avr_sale_price' => 'decimal:2',
        'avr_remain_price' => 'decimal:2',
    ];

    /**
     * Get the warehouse that owns the warehouse product.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the product that owns the warehouse product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to get warehouse products for a specific warehouse.
     */
    public function scopeForWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    /**
     * Scope to get warehouse products for a specific product.
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope to get warehouse products with positive balance.
     */
    public function scopeInStock($query)
    {
        return $query->where('balance', '>', 0);
    }

    /**
     * Scope to get warehouse products with low stock (balance <= minimum quantity).
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('balance', '<=', 'products.minimum_quantity')
                    ->join('products', 'warehouses_products.product_id', '=', 'products.id');
    }

    /**
     * Calculate the total value of this warehouse product.
     */
    public function getTotalValueAttribute(): float
    {
        return $this->balance * $this->avr_remain_price;
    }

    /**
     * Update the average prices based on inventory movements.
     */
    public function updateAveragePrices(float $newBuyPrice, float $newSalePrice, int $quantity = 1): void
    {
        $totalQuantity = $this->balance + $quantity;
        
        if ($totalQuantity > 0) {
            // Calculate weighted average for buy price
            $this->avr_buy_price = (($this->avr_buy_price * $this->balance) + ($newBuyPrice * $quantity)) / $totalQuantity;
            
            // Calculate weighted average for sale price
            $this->avr_sale_price = (($this->avr_sale_price * $this->balance) + ($newSalePrice * $quantity)) / $totalQuantity;
            
            // Update remaining price (usually same as buy price)
            $this->avr_remain_price = $this->avr_buy_price;
        }
        
        $this->save();
    }

    /**
     * Adjust the balance of the warehouse product.
     */
    public function adjustBalance(int $quantity): void
    {
        $this->balance += $quantity;
        $this->save();
    }
}
