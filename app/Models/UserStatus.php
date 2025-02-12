<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sign',
        'color',
    ];

    // 🔹 Relationship: One Status has Many Users
    public function users()
    {
        return $this->hasMany(User::class, 'user_status_id');
    }
}
