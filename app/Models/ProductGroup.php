<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductGroup extends Model
{


    public $timestamps = false;

    protected $fillable = [
        'name',
        'sign',
        'color',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_group_id');
    }
}