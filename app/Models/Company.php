<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_name_th',
        'company_name_en',
        'tax_no',
        'company_logo',
    ];

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
