<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ProductSubUnit extends Model
{


    // Disable default timestamps since the table doesn't have created_at/updated_at
    public $timestamps = false;

    // Mass-assignable attributes
    protected $fillable = [
        'product_id',
        'serial_number',
        'name',
        'buy_price',
        'sale_price',
        'quantity_of_minimum_unit',
    ];

    protected $casts = [
        'buy_price' => 'float',
        'sale_price' => 'float',
        'quantity_of_minimum_unit' => 'integer'
    ];

    /**
     * The product that this sub-unit belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
