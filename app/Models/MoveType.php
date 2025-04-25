<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MoveType extends Model
{
    // Disable default timestamps since the table doesn't have created_at/updated_at
    public $timestamps = false;

    // Mass-assignable attributes
    protected $fillable = [
        'name',
        'sign',
        'color',
    ];

    /**
     * The inventory entries associated with this move type.
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
