<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_no',
        'avatar',
        'nickname',
        'card_id_no',
        'prefix_th',
        'fullname_th',
        'prefix_en',
        'fullname_en',
        'birth_date',
        'description',
    ];

    protected $casts = [
        'birth_date' => 'datetime',
    ];

    // 🔹 Relationship: A Profile belongs to One User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}