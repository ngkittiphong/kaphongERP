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
        $datePart = now()->format('ymd');
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $randomPart = strtoupper(Str::random(4));
            $candidate = 'PRF' . $datePart . '-' . $randomPart; // e.g. PRF250905-AB3X

            if (! static::where('profile_no', $candidate)->exists()) {
                return $candidate;
            }
        }

        return 'PRF' . $datePart . '-' . strtoupper(Str::random(6));
    }

    // 🔹 Relationship: A Profile belongs to One User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}