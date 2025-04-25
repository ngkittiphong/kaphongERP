<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_code',
        'name_th',
        'name_en',
        'address_th',
        'address_en',
        'bill_address_th',
        'bill_address_en',
        'post_code',
        'phone_country_code',
        'phone_number',
        'fax',
        'website',
        'email',
        'is_active',
        'is_head_office',
        'latitude',
        'longitude',
        'contact_name',
        'contact_email',
        'contact_mobile',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
