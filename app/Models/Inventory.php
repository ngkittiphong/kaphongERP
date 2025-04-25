<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    // Disable default timestamps since the table doesn't have created_at/updated_at
    public $timestamps = false;

    // Mass-assignable attributes
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'sale_order_id',
        'transfer_slip_id',
        'contact_id',
        'date_activity',
        'move_type_id',
        'detail',
        'quantity_move',
        'unit_name',
        'remaining',
        'avr_buy_price',
        'avr_sale_price',
        'avr_remain_price',
    ];

    // Casts for date and price fields
    protected $casts = [
        'date_activity'  => 'datetime',
        'avr_buy_price'  => 'float',
        'avr_sale_price' => 'float',
        'avr_remain_price' => 'float',
    ];

    /**
     * The product associated with this inventory entry.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * The move type for this inventory entry.
     */
    public function moveType(): BelongsTo
    {
        return $this->belongsTo(MoveType::class);
    }
}