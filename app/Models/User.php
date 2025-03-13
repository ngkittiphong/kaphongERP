<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_id',
        'user_status_id',
        'user_type_id',
        'request_change_pass',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 🔹 Relationship: One User has One Profile
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    // 🔹 Relationship: One User has One Status
    public function status()
    {
        return $this->belongsTo(UserStatus::class, 'user_status_id');
    }

    // 🔹 Relationship: One User has One Type
    public function type()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    // 🔹 Role Checking Methods
    public function isAdmin()
    {
        return $this->type->name === 'Admin';
    }

    public function isUser()
    {
        return $this->type->name === 'User';
    }

    // 🔹 Scope to Fetch Active Users
    public function scopeActive($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('name', 'Active');
        });
    }

    // 🔹 Mutator for Password Hashing
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}