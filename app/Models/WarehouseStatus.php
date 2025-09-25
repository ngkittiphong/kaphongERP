<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sign',
        'color',
    ];

    // 🔹 Relationship: One Status has Many Warehouses
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'warehouse_status_id');
    }
}
