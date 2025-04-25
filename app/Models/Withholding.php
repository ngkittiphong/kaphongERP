<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Withholding extends Model
{


    protected $table = 'withholdings';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'price_percent',
    ];

    protected $casts = [
        'price_percent' => 'float'
    ];

    public function buyProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'buy_withholding_id');
    }

    public function saleProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'sale_withholding_id');
    }
}
