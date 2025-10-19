<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

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
        return $this->hasRole('super_admin') || ($this->type && $this->type->name === 'Admin');
    }

    public function isUser()
    {
        return $this->hasAnyRole(['super_admin']) || ($this->type && $this->type->name === 'User');
    }

    /**
     * Helper to centralize menu permission checks.
     */
    public function hasMenuAccess(string $permission): bool
    {
        return $this->can($permission);
    }

    // 🔹 Scope to Fetch Active Users
    public function scopeActive($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('name', 'Active');
        });
    }

    // 🔹 Relationship: One User can create many Check Stock Reports
    public function checkStockReportsCreated()
    {
        return $this->hasMany(CheckStockReport::class, 'user_create_id');
    }

    // 🔹 Relationship: One User can perform many Check Stock Details
    public function checkStockDetails()
    {
        return $this->hasMany(CheckStockDetail::class, 'user_check_id');
    }

    // 🔹 Mutator for Password Hashing
    public function setPasswordAttribute($value)
    {
        // Only hash if the password isn't already hashed
        if (strlen($value) < 60 || !preg_match('/^\$2[ayb]\$/', $value)) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }
}
