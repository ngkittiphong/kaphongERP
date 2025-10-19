<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransferSlipStatus extends Model
{
    protected $fillable = [
        'name',
        'sign',
        'color',
    ];

    /**
     * Get the transfer slips with this status.
     */
    public function transferSlips(): HasMany
    {
        return $this->hasMany(TransferSlip::class);
    }

    /**
     * Get the translated name for this status.
     */
    public function getTranslatedNameAttribute(): string
    {
        return __t("transfer_status.{$this->sign}", $this->name);
    }

    /**
     * Get the translated name for a given status name.
     */
    public static function getTranslatedName(string $statusName): string
    {
        $statusMap = [
            'Pending' => __t('transfer_status.pending', 'รอดำเนินการ'),
            'Approved' => __t('transfer_status.approved', 'อนุมัติแล้ว'),
            'In Transit' => __t('transfer_status.in_transit', 'กำลังขนส่ง'),
            'Delivered' => __t('transfer_status.delivered', 'ส่งมอบแล้ว'),
            'Completed' => __t('transfer_status.completed', 'เสร็จสิ้น'),
            'Cancelled' => __t('transfer_status.cancelled', 'ยกเลิก'),
        ];
        
        return $statusMap[$statusName] ?? $statusName;
    }
}
