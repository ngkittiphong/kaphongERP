<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\TranslationService;

class TransferStatusTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $translationService = app(TranslationService::class);
        
        $translations = [
            'transfer_status.pending' => [
                'en' => 'Pending',
                'th' => 'รอจัดสินค้า'
            ],
            'transfer_status.in_transit' => [
                'en' => 'In Transit',
                'th' => 'สินค้าพร้อมส่ง'
            ],
            'transfer_status.delivered' => [
                'en' => 'Delivered',
                'th' => 'ส่งสินค้าแล้ว'
            ],
            'transfer_status.completed' => [
                'en' => 'Completed',
                'th' => 'รับสินค้าเรียบร้อย'
            ],
            'transfer_status.cancelled' => [
                'en' => 'Cancelled',
                'th' => 'ยกเลิก'
            ],
            'transfer.change_status' => [
                'en' => 'Change Status',
                'th' => 'เปลี่ยนสถานะ'
            ],
            'transfer.cancel_transfer' => [
                'en' => 'Cancel Transfer',
                'th' => 'ยกเลิกการโอน'
            ],
            'common.cancel' => [
                'en' => 'Cancel',
                'th' => 'ยกเลิก'
            ],
            'common.confirm_change' => [
                'en' => 'Confirm Change',
                'th' => 'ยืนยันการเปลี่ยนแปลง'
            ],
            'common.confirm_status_change' => [
                'en' => 'Confirm Status Change',
                'th' => 'ยืนยันการเปลี่ยนสถานะ'
            ],
            'common.confirm_status_change_message' => [
                'en' => 'Are you sure you want to change the status of transfer slip',
                'th' => 'คุณแน่ใจหรือไม่ว่าต้องการเปลี่ยนสถานะของใบโอนสินค้า'
            ],
            'common.to' => [
                'en' => 'to',
                'th' => 'เป็น'
            ],
            'transfer.status_updated_successfully' => [
                'en' => 'Transfer slip status updated successfully!',
                'th' => 'อัปเดตสถานะใบโอนสินค้าสำเร็จ!'
            ],
            'transfer.failed_to_update_status' => [
                'en' => 'Failed to update status',
                'th' => 'ไม่สามารถอัปเดตสถานะได้'
            ],
            'transfer.please_provide_cancellation_reason' => [
                'en' => 'Please provide a cancellation reason.',
                'th' => 'กรุณาระบุเหตุผลในการยกเลิก'
            ],
            'transfer.cancellation_reason_too_short' => [
                'en' => 'Cancellation reason must be at least 10 characters.',
                'th' => 'เหตุผลในการยกเลิกต้องมีอย่างน้อย 10 ตัวอักษร'
            ],
            'transfer.cancellation_reason_too_long' => [
                'en' => 'Cancellation reason must not exceed 500 characters.',
                'th' => 'เหตุผลในการยกเลิกต้องไม่เกิน 500 ตัวอักษร'
            ],
            'transfer.cancelled_status_not_found' => [
                'en' => 'Cancelled status not found. Please contact administrator.',
                'th' => 'ไม่พบสถานะยกเลิก กรุณาติดต่อผู้ดูแลระบบ'
            ],
            'transfer.cannot_be_cancelled' => [
                'en' => 'Transfer cannot be cancelled in its current status',
                'th' => 'ไม่สามารถยกเลิกการโอนในสถานะปัจจุบันได้'
            ],
            'transfer.cancelled_successfully' => [
                'en' => 'Transfer cancelled successfully',
                'th' => 'ยกเลิกการโอนสำเร็จ'
            ],
            'transfer.failed_to_cancel' => [
                'en' => 'Failed to cancel transfer',
                'th' => 'ไม่สามารถยกเลิกการโอนได้'
            ],
            'inventory.stock_out_product_not_found' => [
                'en' => 'Stock Out failed: Product not found in warehouse',
                'th' => 'การตัดสต็อกล้มเหลว: ไม่พบสินค้าในคลังสินค้า'
            ],
        ];

        foreach ($translations as $key => $values) {
            foreach ($values as $locale => $value) {
                $translationService->set(
                    $key, 
                    $locale, 
                    $value, 
                    'transfer_status',
                    "Translation for transfer status: {$key}"
                );
            }
        }

        $this->command->info('Created transfer status translations for English and Thai.');
    }
}
