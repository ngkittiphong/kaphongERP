<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_no',
        'avatar',
        'sign_img',
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

    protected static function booted(): void
    {
        static::creating(function (UserProfile $profile) {
            if (!empty($profile->profile_no)) {
                return;
            }

            $profile->profile_no = static::generateProfileNo();
        });
    }

    public static function generateProfileNo(int $maxAttempts = 5): string
    {
        $year = now()->format('Y'); // 4-digit year (e.g., 2025)
        $month = now()->format('m'); // 2-digit month (e.g., 01, 12)
        
        // Get the next available ID from the database
        $nextId = static::max('id') + 1;
        
        // Ensure the ID is 4 digits with leading zeros
        $profileId = str_pad($nextId, 4, '0', STR_PAD_LEFT);
        
        // Generate the final profile number
        $candidate = "HR{$year}{$month}{$profileId}"; // e.g., HR2025010001
        
        // Double-check uniqueness (shouldn't be necessary with database IDs, but safety first)
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            if (!static::where('profile_no', $candidate)->exists()) {
                return $candidate;
            }
            // If somehow it exists, increment and try again
            $nextId++;
            $profileId = str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $candidate = "HR{$year}{$month}{$profileId}";
        }
        
        // Fallback: use timestamp-based ID if all else fails
        $fallbackId = str_pad(now()->timestamp % 10000, 4, '0', STR_PAD_LEFT);
        return "HR{$year}{$month}{$fallbackId}";
    }

    // 🔹 Relationship: A Profile belongs to One User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}