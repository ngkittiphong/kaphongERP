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
            'common.all' => [
                'en' => 'All',
                'th' => 'ทั้งหมด'
            ],
            'common.done' => [
                'en' => 'Done',
                'th' => 'เสร็จสิ้น'
            ],
            'transfer.pick_date' => [
                'en' => 'Pick Date',
                'th' => 'วันที่จัดสินค้า'
            ],
            'transfer.picker' => [
                'en' => 'Picker',
                'th' => 'ผู้จัดสินค้า'
            ],
            'transfer.receive_date' => [
                'en' => 'Receive Date',
                'th' => 'วันที่รับสินค้า'
            ],
            'transfer.receiver' => [
                'en' => 'Receiver',
                'th' => 'ผู้รับสินค้า'
            ],
            'transfer.inbound' => [
                'en' => 'Inbound',
                'th' => 'สินค้าเข้า'
            ],
            'transfer.outbound' => [
                'en' => 'Outbound',
                'th' => 'สินค้าออก'
            ],
            'transfer.add_new_product_transfer' => [
                'en' => 'Add New Product Transfer',
                'th' => 'เพิ่มการโอนสินค้าใหม่'
            ],
            'transfer.transfer_information' => [
                'en' => 'Transfer Information',
                'th' => 'ข้อมูลการโอน'
            ],
            'transfer.transfer_date' => [
                'en' => 'Transfer Date',
                'th' => 'วันที่โอน'
            ],
            'transfer.requested_by' => [
                'en' => 'Requested By',
                'th' => 'ผู้ขอโอน'
            ],
            'transfer.deliver_name' => [
                'en' => 'Deliver Name',
                'th' => 'ชื่อผู้ส่ง'
            ],
            'transfer.origin_warehouse' => [
                'en' => 'Origin Warehouse',
                'th' => 'คลังสินค้าต้นทาง'
            ],
            'transfer.destination_warehouse' => [
                'en' => 'Destination Warehouse',
                'th' => 'คลังสินค้าหมายเลข'
            ],
            'transfer.select_origin_warehouse' => [
                'en' => 'Select Origin Warehouse',
                'th' => 'เลือกคลังสินค้าต้นทาง'
            ],
            'transfer.select_destination_warehouse' => [
                'en' => 'Select Destination Warehouse',
                'th' => 'เลือกคลังสินค้าหมายเลข'
            ],
            'transfer.origin_warehouse_locked' => [
                'en' => 'Origin warehouse is locked after selection',
                'th' => 'คลังสินค้าต้นทางถูกล็อกหลังการเลือก'
            ],
            'transfer.enter_deliver_name' => [
                'en' => 'Enter deliver name',
                'th' => 'กรอกชื่อผู้ส่ง'
            ],
            'transfer.description' => [
                'en' => 'Description',
                'th' => 'คำอธิบาย'
            ],
            'transfer.enter_transfer_description' => [
                'en' => 'Enter transfer description',
                'th' => 'กรอกคำอธิบายการโอน'
            ],
            'transfer.note' => [
                'en' => 'Note',
                'th' => 'หมายเหตุ'
            ],
            'transfer.enter_additional_notes' => [
                'en' => 'Enter additional notes',
                'th' => 'กรอกหมายเหตุเพิ่มเติม'
            ],
            'transfer.product_transfer_details' => [
                'en' => 'Product Transfer Details',
                'th' => 'รายละเอียดการโอนสินค้า'
            ],
            'transfer.select_origin_warehouse_first' => [
                'en' => 'Please select an origin warehouse to add products for transfer.',
                'th' => 'กรุณาเลือกคลังสินค้าต้นทางเพื่อเพิ่มสินค้าสำหรับการโอน'
            ],
            'transfer.product' => [
                'en' => 'Product',
                'th' => 'สินค้า'
            ],
            'transfer.quantity' => [
                'en' => 'Quantity',
                'th' => 'จำนวน'
            ],
            'transfer.unit' => [
                'en' => 'Unit',
                'th' => 'หน่วย'
            ],
            'transfer.cost_per_unit' => [
                'en' => 'Cost/Unit',
                'th' => 'ต้นทุน/หน่วย'
            ],
            'transfer.total_cost' => [
                'en' => 'Total Cost',
                'th' => 'ต้นทุนรวม'
            ],
            'transfer.action' => [
                'en' => 'Action',
                'th' => 'การดำเนินการ'
            ],
            'transfer.search_product_placeholder' => [
                'en' => 'Search product by name or SKU...',
                'th' => 'ค้นหาสินค้าตามชื่อหรือรหัสสินค้า...'
            ],
            'transfer.available' => [
                'en' => 'Available',
                'th' => 'มีอยู่'
            ],
            'transfer.warning_no_stock' => [
                'en' => 'Warning: This product has no available stock in the origin warehouse.',
                'th' => 'คำเตือน: สินค้านี้ไม่มีสต็อกในคลังสินค้าต้นทาง'
            ],
            'transfer.exceeds_available' => [
                'en' => 'Exceeds Available',
                'th' => 'เกินจำนวนที่มี'
            ],
            'transfer.requested' => [
                'en' => 'Requested',
                'th' => 'ขอ'
            ],
            'transfer.but_only_available' => [
                'en' => 'but only',
                'th' => 'แต่มีเพียง'
            ],
            'transfer.available_units' => [
                'en' => 'available',
                'th' => 'มีอยู่'
            ],
            'transfer.add' => [
                'en' => 'Add',
                'th' => 'เพิ่ม'
            ],
            'transfer.total_quantity' => [
                'en' => 'Total Quantity',
                'th' => 'จำนวนรวม'
            ],
            'transfer.next' => [
                'en' => 'Next',
                'th' => 'ถัดไป'
            ],
            'transfer.create_transfer' => [
                'en' => 'Create Transfer',
                'th' => 'สร้างการโอน'
            ],
            'transfer.creating' => [
                'en' => 'Creating...',
                'th' => 'กำลังสร้าง...'
            ],
            'transfer.confirm_transfer_creation' => [
                'en' => 'Confirm Transfer Creation',
                'th' => 'ยืนยันการสร้างการโอน'
            ],
            'transfer.transfer_details' => [
                'en' => 'Transfer Details',
                'th' => 'รายละเอียดการโอนย้าย'
            ],
            'transfer.from' => [
                'en' => 'From',
                'th' => 'จาก'
            ],
            'transfer.to' => [
                'en' => 'To',
                'th' => 'ถึง'
            ],
            'transfer.items' => [
                'en' => 'Items',
                'th' => 'สินค้า'
            ],
            'transfer.total_qty' => [
                'en' => 'Total Qty',
                'th' => 'จำนวนรวม'
            ],
            'transfer.transfer_slip_number' => [
                'en' => 'Transfer Slip Number',
                'th' => 'หมายเลขใบโอนย้าย'
            ],
            'transfer.date' => [
                'en' => 'Date',
                'th' => 'วันที่'
            ],
            'transfer.products_to_transfer' => [
                'en' => 'Products to be transferred',
                'th' => 'สินค้าที่จะโอนย้าย'
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
