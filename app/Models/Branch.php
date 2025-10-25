<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        'is_head_office',
        'branch_status_id',
        'latitude',
        'longitude',
        'contact_name',
        'contact_email',
        'contact_mobile',
    ];

    /**
     * Boot the model and add event listeners
     */
    protected static function booted()
    {
        static::creating(function ($branch) {
            // Auto-generate branch_code if not provided
            if (empty($branch->branch_code)) {
                $branch->branch_code = static::generateBranchCode($branch->company_id);
                \Log::debug('Auto-generated branch code during creation', [
                    'branch_code' => $branch->branch_code,
                    'branch_name' => $branch->name_en ?? $branch->name_th ?? 'Unknown'
                ]);
            }

            // Default status to Active (1) if not provided
            if (empty($branch->branch_status_id) && $branch->branch_status_id !== 0) {
                $branch->branch_status_id = 1;
            }
        });

        static::created(function ($branch) {
            // Automatically create main warehouse for the new branch
            try {
                Warehouse::create([
                    'branch_id' => $branch->id,
                    'name' => 'คลัง' . $branch->name_th,
                    'main_warehouse' => true,
                    'warehouse_status_id' => 1, // Active status
                    'date_create' => now(),
                    'user_create_id' => auth()->id(),
                ]);

                \Log::info('Main warehouse created automatically for branch', [
                    'branch_id' => $branch->id,
                    'branch_name_th' => $branch->name_th,
                    'warehouse_name' => 'คลัง' . $branch->name_th
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create main warehouse for branch', [
                    'branch_id' => $branch->id,
                    'branch_name_th' => $branch->name_th,
                    'error' => $e->getMessage()
                ]);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(BranchStatus::class, 'branch_status_id');
    }

    /**
     * Scope a query to only include active branches (exclude status 0).
     */
    public function scopeActive($query)
    {
        // Active means status id = 1 (see BranchStatusSeeder)
        return $query->where('branch_status_id', 1);
    }

    /**
     * Generate a branch code using the former branch number pattern.
     * Format: BR + YYYY + MM + CC + SSS
     * - CC: 2-digit company_id (zero-padded)
     * - SSS: 3-digit sequential count of branches for that company (zero-padded)
     * Note: No retry/uniqueness enforcement here. Uniqueness is validated at save time.
     */
    public static function generateBranchCode(?int $companyId = null): string
    {
        $prefix = 'BR';
        $year = now()->format('Y');
        $month = now()->format('m');

        $companyId = $companyId ?? 1;
        $companyIdPadded = str_pad($companyId, 2, '0', STR_PAD_LEFT);

        $sequence = static::where('company_id', $companyId)->count() + 1;
        $sequencePadded = str_pad($sequence, 3, '0', STR_PAD_LEFT);

        return $prefix . $year . $month . $companyIdPadded . $sequencePadded;
    }
}
