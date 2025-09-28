<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TranslationKey;
use App\Models\TranslationValue;
use App\Services\TranslationService;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translationService = app(TranslationService::class);
        
        // Sidebar translations
        $sidebarTranslations = [
            'sidebar.compose_new_mail' => [
                'en' => 'Compose new mail',
                'th' => 'เขียนอีเมลใหม่'
            ],
            'sidebar.inbox' => [
                'en' => 'Inbox',
                'th' => 'กล่องขาเข้า'
            ],
            'sidebar.sent' => [
                'en' => 'Sent',
                'th' => 'ส่งแล้ว'
            ],
            'sidebar.draft' => [
                'en' => 'Draft',
                'th' => 'ร่าง'
            ],
            'sidebar.trash' => [
                'en' => 'Trash',
                'th' => 'ถังขยะ'
            ],
            'sidebar.folders' => [
                'en' => 'Folders',
                'th' => 'โฟลเดอร์'
            ],
            'sidebar.home' => [
                'en' => 'Home',
                'th' => 'หน้าแรก'
            ],
            'sidebar.work' => [
                'en' => 'Work',
                'th' => 'งาน'
            ],
            'sidebar.documents' => [
                'en' => 'Documents',
                'th' => 'เอกสาร'
            ],
            'sidebar.images' => [
                'en' => 'Images',
                'th' => 'รูปภาพ'
            ],
            'sidebar.flagged' => [
                'en' => 'Flagged',
                'th' => 'ทำเครื่องหมาย'
            ],
            'sidebar.completeness_stats' => [
                'en' => 'Completeness stats',
                'th' => 'สถิติความสมบูรณ์'
            ],
            'sidebar.disk_space_used' => [
                'en' => 'Disk space used',
                'th' => 'พื้นที่ดิสก์ที่ใช้'
            ],
            'sidebar.complete' => [
                'en' => 'Complete',
                'th' => 'เสร็จสิ้น'
            ]
        ];

        // Common UI translations
        $commonTranslations = [
            'common.save' => [
                'en' => 'Save',
                'th' => 'บันทึก'
            ],
            'common.cancel' => [
                'en' => 'Cancel',
                'th' => 'ยกเลิก'
            ],
            'common.delete' => [
                'en' => 'Delete',
                'th' => 'ลบ'
            ],
            'common.edit' => [
                'en' => 'Edit',
                'th' => 'แก้ไข'
            ],
            'common.add' => [
                'en' => 'Add',
                'th' => 'เพิ่ม'
            ],
            'common.search' => [
                'en' => 'Search',
                'th' => 'ค้นหา'
            ],
            'common.filter' => [
                'en' => 'Filter',
                'th' => 'กรอง'
            ],
            'common.clear' => [
                'en' => 'Clear',
                'th' => 'ล้าง'
            ],
            'common.submit' => [
                'en' => 'Submit',
                'th' => 'ส่ง'
            ],
            'common.reset' => [
                'en' => 'Reset',
                'th' => 'รีเซ็ต'
            ],
            'common.close' => [
                'en' => 'Close',
                'th' => 'ปิด'
            ],
            'common.confirm' => [
                'en' => 'Confirm',
                'th' => 'ยืนยัน'
            ],
            'common.yes' => [
                'en' => 'Yes',
                'th' => 'ใช่'
            ],
            'common.no' => [
                'en' => 'No',
                'th' => 'ไม่'
            ],
            'common.loading' => [
                'en' => 'Loading...',
                'th' => 'กำลังโหลด...'
            ],
            'common.error' => [
                'en' => 'Error',
                'th' => 'ข้อผิดพลาด'
            ],
            'common.success' => [
                'en' => 'Success',
                'th' => 'สำเร็จ'
            ],
            'common.warning' => [
                'en' => 'Warning',
                'th' => 'คำเตือน'
            ],
            'common.info' => [
                'en' => 'Information',
                'th' => 'ข้อมูล'
            ],
            'common.save_changes' => [
                'en' => 'Save Changes',
                'th' => 'บันทึกการเปลี่ยนแปลง'
            ],
            'common.home' => [
                'en' => 'Home',
                'th' => 'หน้าแรก'
            ],
            'common.first' => [
                'en' => 'First',
                'th' => 'แรก'
            ],
            'common.last' => [
                'en' => 'Last',
                'th' => 'สุดท้าย'
            ],
            'common.find' => [
                'en' => 'Find',
                'th' => 'ค้นหา'
            ],
            'common.ok' => [
                'en' => 'OK',
                'th' => 'ตกลง'
            ],
            'common.type_to_search' => [
                'en' => 'Type to search...',
                'th' => 'พิมพ์เพื่อค้นหา...'
            ],
            'common.support' => [
                'en' => 'Support',
                'th' => 'สนับสนุน'
            ],
            'common.settings' => [
                'en' => 'Settings',
                'th' => 'การตั้งค่า'
            ],
            'common.account_security' => [
                'en' => 'Account security',
                'th' => 'ความปลอดภัยบัญชี'
            ],
            'common.analytics' => [
                'en' => 'Analytics',
                'th' => 'การวิเคราะห์'
            ],
            'common.accessibility' => [
                'en' => 'Accessibility',
                'th' => 'การเข้าถึง'
            ],
            'common.all_settings' => [
                'en' => 'All settings',
                'th' => 'การตั้งค่าทั้งหมด'
            ],
            'common.unknown_error' => [
                'en' => 'Unknown error',
                'th' => 'ข้อผิดพลาดที่ไม่ทราบ'
            ],
            'common.are_you_sure' => [
                'en' => 'Are you sure?',
                'th' => 'คุณแน่ใจหรือไม่?'
            ],
            'common.action_cannot_be_undone' => [
                'en' => 'This action cannot be undone!',
                'th' => 'การดำเนินการนี้ไม่สามารถยกเลิกได้!'
            ],
            'common.yes_delete_it' => [
                'en' => 'Yes, delete it!',
                'th' => 'ใช่ ลบเลย!'
            ],
            'common.all_rights_reserved' => [
                'en' => 'All rights reserved',
                'th' => 'สงวนลิขสิทธิ์'
            ]
        ];

        // Profile translations
        $profileTranslations = [
            'profile.click_or_drag_photo' => [
                'en' => 'Click or drag your photo',
                'th' => 'คลิกหรือลากรูปภาพของคุณ'
            ],
            'profile.change_nickname' => [
                'en' => 'Change Nickname',
                'th' => 'เปลี่ยนชื่อเล่น'
            ],
            'profile.change_password' => [
                'en' => 'Change Password',
                'th' => 'เปลี่ยนรหัสผ่าน'
            ],
            'profile.sign_name' => [
                'en' => 'Sign name',
                'th' => 'ชื่อลายเซ็น'
            ],
            'profile.new_nickname' => [
                'en' => 'New Nickname',
                'th' => 'ชื่อเล่นใหม่'
            ]
        ];

        // Dashboard translations
        $dashboardTranslations = [
            'dashboard.title' => [
                'en' => 'Dashboard',
                'th' => 'แดชบอร์ด'
            ],
            'dashboard.default_dashboard' => [
                'en' => 'Default Dashboard',
                'th' => 'แดชบอร์ดเริ่มต้น'
            ],
            'dashboard.earnings_graph' => [
                'en' => 'Earnings graph',
                'th' => 'กราฟรายได้'
            ],
            'dashboard.total_earnings_this_year' => [
                'en' => 'Total earnings this year',
                'th' => 'รายได้รวมปีนี้'
            ],
            'dashboard.america' => [
                'en' => 'America',
                'th' => 'อเมริกา'
            ],
            'dashboard.europe' => [
                'en' => 'Europe',
                'th' => 'ยุโรป'
            ],
            'dashboard.asia' => [
                'en' => 'Asia',
                'th' => 'เอเชีย'
            ],
            'dashboard.april_2016' => [
                'en' => 'April 2016',
                'th' => 'เมษายน 2559'
            ],
            'dashboard.direct_sell' => [
                'en' => 'Direct Sell',
                'th' => 'ขายตรง'
            ],
            'dashboard.channel_sell' => [
                'en' => 'Channel Sell',
                'th' => 'ขายผ่านช่องทาง'
            ],
            'dashboard.expected_sales' => [
                'en' => 'Expected Sales',
                'th' => 'ยอดขายที่คาดหวัง'
            ],
            'dashboard.new_york_rainy' => [
                'en' => 'New York (Rainy)',
                'th' => 'นิวยอร์ก (ฝนตก)'
            ],
            'dashboard.today' => [
                'en' => 'Today',
                'th' => 'วันนี้'
            ],
            'dashboard.mon' => [
                'en' => 'Mon',
                'th' => 'จ.'
            ],
            'dashboard.tue' => [
                'en' => 'Tue',
                'th' => 'อ.'
            ],
            'dashboard.wed' => [
                'en' => 'Wed',
                'th' => 'พ.'
            ],
            'dashboard.thu' => [
                'en' => 'Thu',
                'th' => 'พฤ.'
            ],
            'dashboard.fri' => [
                'en' => 'Fri',
                'th' => 'ศ.'
            ],
            'dashboard.sat' => [
                'en' => 'Sat',
                'th' => 'ส.'
            ],
            'dashboard.recent_activities' => [
                'en' => 'Recent Activities',
                'th' => 'กิจกรรมล่าสุด'
            ],
            'dashboard.latest_users' => [
                'en' => 'Latest users',
                'th' => 'ผู้ใช้ล่าสุด'
            ],
            'dashboard.first_name' => [
                'en' => 'First Name',
                'th' => 'ชื่อ'
            ],
            'dashboard.last_name' => [
                'en' => 'Last Name',
                'th' => 'นามสกุล'
            ],
            'dashboard.username' => [
                'en' => 'Username',
                'th' => 'ชื่อผู้ใช้'
            ]
        ];

        // Product translations
        $productTranslations = [
            'product.add_new_product' => [
                'en' => 'Add new product',
                'th' => 'เพิ่มสินค้าใหม่'
            ],
            'product.product_name' => [
                'en' => 'Product Name',
                'th' => 'ชื่อสินค้า'
            ],
            'product.sku_number' => [
                'en' => 'SKU Number',
                'th' => 'หมายเลข SKU'
            ],
            'product.serial_number' => [
                'en' => 'Serial Number',
                'th' => 'หมายเลขซีเรียล'
            ],
            'product.product_type' => [
                'en' => 'Product Type',
                'th' => 'ประเภทสินค้า'
            ],
            'product.select_type' => [
                'en' => 'Select Type',
                'th' => 'เลือกประเภท'
            ],
            'product.product_group' => [
                'en' => 'Product Group',
                'th' => 'กลุ่มสินค้า'
            ],
            'product.select_group' => [
                'en' => 'Select Group',
                'th' => 'เลือกกลุ่ม'
            ],
            'product.product_status' => [
                'en' => 'Product Status',
                'th' => 'สถานะสินค้า'
            ],
            'product.select_status' => [
                'en' => 'Select Status',
                'th' => 'เลือกสถานะ'
            ],
            'product.unit_name' => [
                'en' => 'Unit Name',
                'th' => 'ชื่อหน่วย'
            ],
            'product.buy_price' => [
                'en' => 'Buy Price',
                'th' => 'ราคาซื้อ'
            ],
            'product.buy_vat' => [
                'en' => 'Buy VAT',
                'th' => 'ภาษีมูลค่าเพิ่มซื้อ'
            ],
            'product.select_vat' => [
                'en' => 'Select VAT',
                'th' => 'เลือกภาษีมูลค่าเพิ่ม'
            ],
            'product.buy_withholding' => [
                'en' => 'Buy Withholding',
                'th' => 'ภาษีหัก ณ ที่จ่ายซื้อ'
            ],
            'product.select_withholding' => [
                'en' => 'Select Withholding',
                'th' => 'เลือกภาษีหัก ณ ที่จ่าย'
            ],
            'product.sale_price' => [
                'en' => 'Sale Price',
                'th' => 'ราคาขาย'
            ],
            'product.sale_vat' => [
                'en' => 'Sale VAT',
                'th' => 'ภาษีมูลค่าเพิ่มขาย'
            ],
            'product.sale_withholding' => [
                'en' => 'Sale Withholding',
                'th' => 'ภาษีหัก ณ ที่จ่ายขาย'
            ],
            'product.minimum_quantity' => [
                'en' => 'Minimum Quantity',
                'th' => 'จำนวนขั้นต่ำ'
            ],
            'product.maximum_quantity' => [
                'en' => 'Maximum Quantity',
                'th' => 'จำนวนสูงสุด'
            ],
            'product.buy_description' => [
                'en' => 'Buy Description',
                'th' => 'คำอธิบายการซื้อ'
            ],
            'product.sale_description' => [
                'en' => 'Sale Description',
                'th' => 'คำอธิบายการขาย'
            ],
            'product.update_product' => [
                'en' => 'Update Product',
                'th' => 'อัปเดตสินค้า'
            ],
            'product.edit_product' => [
                'en' => 'Edit Product',
                'th' => 'แก้ไขสินค้า'
            ],
            'product.no_products_found' => [
                'en' => 'No products found.',
                'th' => 'ไม่พบสินค้า'
            ],
            'product.products_count' => [
                'en' => 'Products count',
                'th' => 'จำนวนสินค้า'
            ],
            'product.product_details' => [
                'en' => 'Product details',
                'th' => 'รายละเอียดสินค้า'
            ],
            'product.delete_product' => [
                'en' => 'Delete Product',
                'th' => 'ลบสินค้า'
            ],
            'product.total_count' => [
                'en' => 'Total Count',
                'th' => 'จำนวนรวม'
            ],
            'product.product_unit' => [
                'en' => 'Product Unit',
                'th' => 'หน่วยสินค้า'
            ],
            'product.total_all_warehouses' => [
                'en' => 'Total All Warehouses',
                'th' => 'รวมทุกคลังสินค้า'
            ],
            'product.remaining_quantity' => [
                'en' => 'Remaining Quantity',
                'th' => 'จำนวนคงเหลือ'
            ],
            'product.average_sale_price' => [
                'en' => 'Average Sale Price',
                'th' => 'ราคาขายเฉลี่ย'
            ],
            'product.average_buy_price' => [
                'en' => 'Average Buy Price',
                'th' => 'ราคาซื้อเฉลี่ย'
            ],
            'product.total_value' => [
                'en' => 'Total Value',
                'th' => 'มูลค่ารวม'
            ],
            'product.main' => [
                'en' => 'Main',
                'th' => 'หลัก'
            ],
            'product.adjust_stock' => [
                'en' => 'Adjust Stock',
                'th' => 'ปรับสต็อก'
            ],
            'product.no_warehouse_data_found' => [
                'en' => 'No Warehouse Data Found',
                'th' => 'ไม่พบข้อมูลคลังสินค้า'
            ],
            'product.no_inventory_data_message' => [
                'en' => 'This product has no inventory data in any warehouse',
                'th' => 'สินค้านี้ไม่มีข้อมูลสินค้าคงคลังในคลังสินค้าใดๆ'
            ],
            'product.add_warehouse_data' => [
                'en' => 'Add Warehouse Data',
                'th' => 'เพิ่มข้อมูลคลังสินค้า'
            ],
            'product.other_warehouses' => [
                'en' => 'Other Warehouses',
                'th' => 'คลังสินค้าอื่นๆ'
            ],
            'product.warehouses' => [
                'en' => 'warehouses',
                'th' => 'คลังสินค้า'
            ],
            'product.main_product_unit' => [
                'en' => 'Main Product Unit',
                'th' => 'หน่วยสินค้าหลัก'
            ],
            'product.barcode' => [
                'en' => 'Barcode',
                'th' => 'บาร์โค้ด'
            ],
            'product.no_sub_units' => [
                'en' => 'No Sub-Units',
                'th' => 'ไม่มีหน่วยย่อย'
            ],
            'product.only_main_unit_message' => [
                'en' => 'This product has only the main unit',
                'th' => 'สินค้านี้มีเฉพาะหน่วยหลักเท่านั้น'
            ],
            'product.other_sub_units' => [
                'en' => 'Other Sub-Units',
                'th' => 'หน่วยย่อยอื่นๆ'
            ],
            'product.units' => [
                'en' => 'units',
                'th' => 'หน่วย'
            ],
            'product.operation_type' => [
                'en' => 'Operation Type',
                'th' => 'ประเภทการดำเนินการ'
            ],
            'product.select_operation' => [
                'en' => 'Select Operation',
                'th' => 'เลือกการดำเนินการ'
            ],
            'product.stock_in' => [
                'en' => 'Stock In',
                'th' => 'รับเข้าสต็อก'
            ],
            'product.stock_out' => [
                'en' => 'Stock Out',
                'th' => 'ส่งออกสต็อก'
            ],
            'product.stock_adjustment' => [
                'en' => 'Stock Adjustment',
                'th' => 'ปรับปรุงสต็อก'
            ],
            'product.quantity' => [
                'en' => 'Quantity',
                'th' => 'จำนวน'
            ],
            'product.enter_quantity' => [
                'en' => 'Enter quantity',
                'th' => 'ป้อนจำนวน'
            ],
            'product.unit' => [
                'en' => 'Unit',
                'th' => 'หน่วย'
            ],
            'product.unit_price' => [
                'en' => 'Unit Price',
                'th' => 'ราคาต่อหน่วย'
            ],
            'product.enter_unit_price' => [
                'en' => 'Enter unit price',
                'th' => 'ป้อนราคาต่อหน่วย'
            ],
            'product.enter_sale_price' => [
                'en' => 'Enter sale price',
                'th' => 'ป้อนราคาขาย'
            ],
            'product.detail_reason' => [
                'en' => 'Detail/Reason',
                'th' => 'รายละเอียด/เหตุผล'
            ],
            'product.enter_reason' => [
                'en' => 'Enter reason for this operation',
                'th' => 'ป้อนเหตุผลสำหรับการดำเนินการนี้'
            ],
            'product.current_stock_information' => [
                'en' => 'Current Stock Information',
                'th' => 'ข้อมูลสต็อกปัจจุบัน'
            ],
            'product.warehouse' => [
                'en' => 'Warehouse',
                'th' => 'คลังสินค้า'
            ],
            'product.current_remaining' => [
                'en' => 'Current Remaining',
                'th' => 'คงเหลือปัจจุบัน'
            ],
            'product.operation_date' => [
                'en' => 'Operation Date',
                'th' => 'วันที่ดำเนินการ'
            ],
            'product.date' => [
                'en' => 'Date',
                'th' => 'วันที่'
            ],
            'product.time' => [
                'en' => 'Time',
                'th' => 'เวลา'
            ],
            'product.process' => [
                'en' => 'Process',
                'th' => 'ดำเนินการ'
            ],
            'product.processing' => [
                'en' => 'Processing',
                'th' => 'กำลังดำเนินการ'
            ],
            'product.back_to_warehouse_inventory' => [
                'en' => 'Back to Warehouse Inventory',
                'th' => 'กลับไปยังสินค้าคงคลังคลังสินค้า'
            ],
            'product.detail' => [
                'en' => 'Detail',
                'th' => 'รายละเอียด'
            ],
            'product.stock_card' => [
                'en' => 'Stock Card',
                'th' => 'บัตรสต็อก'
            ],
            'product.trading_detail' => [
                'en' => 'Trading Detail',
                'th' => 'รายละเอียดการซื้อขาย'
            ],
            'product.select_product_to_view_stock_card' => [
                'en' => 'Please select a product to view stock card details.',
                'th' => 'กรุณาเลือกสินค้าเพื่อดูรายละเอียดบัตรสต็อก'
            ],
            'product.select_product_to_view_details' => [
                'en' => 'Select a product to view details',
                'th' => 'เลือกสินค้าเพื่อดูรายละเอียด'
            ],
            'product.confirm_stock_operation' => [
                'en' => 'Confirm Stock Operation',
                'th' => 'ยืนยันการดำเนินการสต็อก'
            ],
            'product.confirm_operation' => [
                'en' => 'Confirm Operation',
                'th' => 'ยืนยันการดำเนินการ'
            ],
            'product.stock_card_detail_statement' => [
                'en' => 'Stock Card Detail Statement',
                'th' => 'รายการรายละเอียดบัตรสต็อก'
            ],
            'product.start_date' => [
                'en' => 'Start Date',
                'th' => 'วันที่เริ่มต้น'
            ],
            'product.select_start_date' => [
                'en' => 'Select start date',
                'th' => 'เลือกวันที่เริ่มต้น'
            ],
            'product.end_date' => [
                'en' => 'End Date',
                'th' => 'วันที่สิ้นสุด'
            ],
            'product.select_end_date' => [
                'en' => 'Select end date',
                'th' => 'เลือกวันที่สิ้นสุด'
            ],
            'product.select_branch_warehouse' => [
                'en' => 'Select Branch & Warehouse',
                'th' => 'เลือกสาขาและคลังสินค้า'
            ],
            'product.all_branches_warehouses' => [
                'en' => 'All Branches & Warehouses',
                'th' => 'สาขาและคลังสินค้าทั้งหมด'
            ],
            'product.select_all_warehouses_in' => [
                'en' => 'Select All Warehouses in',
                'th' => 'เลือกคลังสินค้าทั้งหมดใน'
            ],
            'product.branch_warehouse_help_text' => [
                'en' => 'Select a branch to include all its warehouses, or select a specific warehouse.',
                'th' => 'เลือกสาขาเพื่อรวมคลังสินค้าทั้งหมด หรือเลือกคลังสินค้าเฉพาะ'
            ],
            'product.remaining_stock' => [
                'en' => 'Remaining Stock',
                'th' => 'สต็อกคงเหลือ'
            ],
            'product.incoming_stock' => [
                'en' => 'Incoming Stock',
                'th' => 'สต็อกเข้า'
            ],
            'product.outgoing_stock' => [
                'en' => 'Outgoing Stock',
                'th' => 'สต็อกออก'
            ],
            'product.move' => [
                'en' => 'Move',
                'th' => 'การเคลื่อนไหว'
            ],
            'product.document_no' => [
                'en' => 'Document No.',
                'th' => 'หมายเลขเอกสาร'
            ],
            'product.quantity_in' => [
                'en' => 'Quantity In',
                'th' => 'จำนวนเข้า'
            ],
            'product.quantity_out' => [
                'en' => 'Quantity Out',
                'th' => 'จำนวนออก'
            ],
            'product.in' => [
                'en' => 'In',
                'th' => 'เข้า'
            ],
            'product.out' => [
                'en' => 'Out',
                'th' => 'ออก'
            ],
            'product.view_document' => [
                'en' => 'View Document',
                'th' => 'ดูเอกสาร'
            ],
            'product.no_stock_movements_found' => [
                'en' => 'No stock movements found for the selected period.',
                'th' => 'ไม่พบการเคลื่อนไหวของสต็อกในช่วงเวลาที่เลือก'
            ],
            'product.total' => [
                'en' => 'Total',
                'th' => 'รวม'
            ],
            'product.remaining' => [
                'en' => 'Remaining',
                'th' => 'คงเหลือ'
            ]
        ];

        // Menu translations
        $menuTranslations = [
            'menu.main' => [
                'en' => 'Main',
                'th' => 'หลัก'
            ],
            'menu.dashboards' => [
                'en' => 'Dashboards',
                'th' => 'แดชบอร์ด'
            ],
            'menu.analytical_dashboard' => [
                'en' => 'Analytical dashboard',
                'th' => 'แดชบอร์ดวิเคราะห์'
            ],
            'menu.products' => [
                'en' => 'Products',
                'th' => 'สินค้า'
            ],
            'menu.category' => [
                'en' => 'Category',
                'th' => 'หมวดหมู่'
            ],
            'menu.transfer' => [
                'en' => 'Transfer',
                'th' => 'โอนย้าย'
            ],
            'menu.user_management' => [
                'en' => 'User Management',
                'th' => 'จัดการผู้ใช้'
            ],
            'menu.users_list' => [
                'en' => 'Users list',
                'th' => 'รายชื่อผู้ใช้'
            ],
            'menu.sell' => [
                'en' => 'Sell',
                'th' => 'ขาย'
            ],
            'menu.pos' => [
                'en' => 'POS',
                'th' => 'ระบบขายหน้าร้าน'
            ],
            'menu.branch' => [
                'en' => 'Branch',
                'th' => 'สาขา'
            ],
            'menu.warehouse' => [
                'en' => 'Warehouse',
                'th' => 'คลังสินค้า'
            ],
            'menu.check_stock' => [
                'en' => 'Check Stock',
                'th' => 'ตรวจสอบสต็อก'
            ],
            'menu.stock_operations' => [
                'en' => 'Stock Operations',
                'th' => 'การดำเนินการสต็อก'
            ],
            'menu.dashboard' => [
                'en' => 'Dashboard',
                'th' => 'แดชบอร์ด'
            ],
            'menu.users' => [
                'en' => 'Users',
                'th' => 'ผู้ใช้'
            ],
            'menu.categories' => [
                'en' => 'Categories',
                'th' => 'หมวดหมู่'
            ],
            'menu.warehouses' => [
                'en' => 'Warehouses',
                'th' => 'คลังสินค้า'
            ],
            'menu.inventory' => [
                'en' => 'Inventory',
                'th' => 'สินค้าคงคลัง'
            ],
            'menu.transfers' => [
                'en' => 'Transfers',
                'th' => 'การโอนย้าย'
            ],
            'menu.reports' => [
                'en' => 'Reports',
                'th' => 'รายงาน'
            ],
            'menu.settings' => [
                'en' => 'Settings',
                'th' => 'การตั้งค่า'
            ],
            'menu.branches' => [
                'en' => 'Branches',
                'th' => 'สาขา'
            ],
            'menu.user' => [
                'en' => 'User',
                'th' => 'ผู้ใช้'
            ],
            'menu.transfer_product' => [
                'en' => 'Transfer Product',
                'th' => 'โอนย้ายสินค้า'
            ]
        ];

        // Branch translations
        $branchTranslations = [
            'branch.add_new_branch' => [
                'en' => 'Add new branch',
                'th' => 'เพิ่มสาขาใหม่'
            ],
            'branch.select_branch_to_view_details' => [
                'en' => 'Select a branch to view details',
                'th' => 'เลือกสาขาเพื่อดูรายละเอียด'
            ],
            'branch.choose_branch_from_list' => [
                'en' => 'Choose a branch from the list to see its information',
                'th' => 'เลือกสาขาจากรายการเพื่อดูข้อมูล'
            ],
            'branch.company' => [
                'en' => 'Company',
                'th' => 'บริษัท'
            ],
            'branch.select_company' => [
                'en' => 'Select Company',
                'th' => 'เลือกบริษัท'
            ],
            'branch.enter_branch_code' => [
                'en' => 'Enter branch code',
                'th' => 'ป้อนรหัสสาขา'
            ],
            'branch.branch_name_th' => [
                'en' => 'Branch Name (Thai)',
                'th' => 'ชื่อสาขา (ไทย)'
            ],
            'branch.enter_thai_name' => [
                'en' => 'Enter Thai name',
                'th' => 'ป้อนชื่อไทย'
            ],
            'branch.branch_name_en' => [
                'en' => 'Branch Name (English)',
                'th' => 'ชื่อสาขา (อังกฤษ)'
            ],
            'branch.enter_english_name' => [
                'en' => 'Enter English name',
                'th' => 'ป้อนชื่ออังกฤษ'
            ],
            'branch.address_th' => [
                'en' => 'Address (Thai)',
                'th' => 'ที่อยู่ (ไทย)'
            ],
            'branch.enter_thai_address' => [
                'en' => 'Enter Thai address',
                'th' => 'ป้อนที่อยู่ไทย'
            ],
            'branch.address_en' => [
                'en' => 'Address (English)',
                'th' => 'ที่อยู่ (อังกฤษ)'
            ],
            'branch.enter_english_address' => [
                'en' => 'Enter English address',
                'th' => 'ป้อนที่อยู่อังกฤษ'
            ],
            'branch.phone_number' => [
                'en' => 'Phone Number',
                'th' => 'หมายเลขโทรศัพท์'
            ],
            'branch.enter_phone_number' => [
                'en' => 'Enter phone number',
                'th' => 'ป้อนหมายเลขโทรศัพท์'
            ],
            'branch.enter_email' => [
                'en' => 'Enter email',
                'th' => 'ป้อนอีเมล'
            ],
            'branch.contact_name' => [
                'en' => 'Contact Name',
                'th' => 'ชื่อผู้ติดต่อ'
            ],
            'branch.enter_contact_name' => [
                'en' => 'Enter contact name',
                'th' => 'ป้อนชื่อผู้ติดต่อ'
            ],
            'branch.contact_mobile' => [
                'en' => 'Contact Mobile',
                'th' => 'มือถือผู้ติดต่อ'
            ],
            'branch.enter_contact_mobile' => [
                'en' => 'Enter contact mobile',
                'th' => 'ป้อนมือถือผู้ติดต่อ'
            ],
            'branch.active' => [
                'en' => 'Active',
                'th' => 'ใช้งาน'
            ],
            'branch.save_branch' => [
                'en' => 'Save Branch',
                'th' => 'บันทึกสาขา'
            ],
            'branch.back_to_details' => [
                'en' => 'Back to Details',
                'th' => 'กลับไปยังรายละเอียด'
            ],
            'branch.billing_address_th' => [
                'en' => 'Billing Address (Thai)',
                'th' => 'ที่อยู่ออกใบเสร็จ (ไทย)'
            ],
            'branch.enter_thai_billing_address' => [
                'en' => 'Enter Thai billing address',
                'th' => 'ป้อนที่อยู่ออกใบเสร็จไทย'
            ],
            'branch.billing_address_en' => [
                'en' => 'Billing Address (English)',
                'th' => 'ที่อยู่ออกใบเสร็จ (อังกฤษ)'
            ],
            'branch.enter_english_billing_address' => [
                'en' => 'Enter English billing address',
                'th' => 'ป้อนที่อยู่ออกใบเสร็จอังกฤษ'
            ],
            'branch.postal_code' => [
                'en' => 'Postal Code',
                'th' => 'รหัสไปรษณีย์'
            ],
            'branch.enter_postal_code' => [
                'en' => 'Enter postal code',
                'th' => 'ป้อนรหัสไปรษณีย์'
            ],
            'branch.phone_country_code' => [
                'en' => 'Phone Country Code',
                'th' => 'รหัสประเทศโทรศัพท์'
            ],
            'branch.fax' => [
                'en' => 'Fax',
                'th' => 'แฟกซ์'
            ],
            'branch.enter_fax_number' => [
                'en' => 'Enter fax number',
                'th' => 'ป้อนหมายเลขแฟกซ์'
            ],
            'branch.website' => [
                'en' => 'Website',
                'th' => 'เว็บไซต์'
            ],
            'branch.enter_website_url' => [
                'en' => 'Enter website URL',
                'th' => 'ป้อน URL เว็บไซต์'
            ],
            'branch.contact_email' => [
                'en' => 'Contact Email',
                'th' => 'อีเมลผู้ติดต่อ'
            ],
            'branch.enter_contact_email' => [
                'en' => 'Enter contact email',
                'th' => 'ป้อนอีเมลผู้ติดต่อ'
            ],
            'branch.latitude' => [
                'en' => 'Latitude',
                'th' => 'ลินทิจูด'
            ],
            'branch.enter_latitude' => [
                'en' => 'Enter latitude',
                'th' => 'ป้อนลินทิจูด'
            ],
            'branch.longitude' => [
                'en' => 'Longitude',
                'th' => 'ลองจิจูด'
            ],
            'branch.enter_longitude' => [
                'en' => 'Enter longitude',
                'th' => 'ป้อนลองจิจูด'
            ],
            'branch.active_branch' => [
                'en' => 'Active Branch',
                'th' => 'สาขาที่ใช้งาน'
            ],
            'branch.update_branch' => [
                'en' => 'Update Branch',
                'th' => 'อัปเดตสาขา'
            ]
        ];

        // User translations
        $userTranslations = [
            'user.add_new_user' => [
                'en' => 'Add new user',
                'th' => 'เพิ่มผู้ใช้ใหม่'
            ],
            'user.username' => [
                'en' => 'Username',
                'th' => 'ชื่อผู้ใช้'
            ],
            'user.email' => [
                'en' => 'Email',
                'th' => 'อีเมล'
            ],
            'user.password' => [
                'en' => 'Password',
                'th' => 'รหัสผ่าน'
            ],
            'user.confirm_password' => [
                'en' => 'Confirm password',
                'th' => 'ยืนยันรหัสผ่าน'
            ],
            'user.fullname_th' => [
                'en' => 'ชื่อ - นามสกุล',
                'th' => 'ชื่อ - นามสกุล'
            ],
            'user.fullname_en' => [
                'en' => 'Fullname',
                'th' => 'ชื่อเต็ม'
            ],
            'user.nickname' => [
                'en' => 'Nickname',
                'th' => 'ชื่อเล่น'
            ],
            'user.description' => [
                'en' => 'Description',
                'th' => 'คำอธิบาย'
            ],
            'user.create_now' => [
                'en' => 'Create now',
                'th' => 'สร้างตอนนี้'
            ],
            'user.user_details' => [
                'en' => 'User details',
                'th' => 'รายละเอียดผู้ใช้'
            ],
            'user.edit_user' => [
                'en' => 'Edit User',
                'th' => 'แก้ไขผู้ใช้'
            ],
            'user.delete_user' => [
                'en' => 'Delete User',
                'th' => 'ลบผู้ใช้'
            ],
            'user.change_password' => [
                'en' => 'Change Password',
                'th' => 'เปลี่ยนรหัสผ่าน'
            ],
            'user.contact_details' => [
                'en' => 'Contact details',
                'th' => 'รายละเอียดการติดต่อ'
            ],
            'user.new_password' => [
                'en' => 'New Password',
                'th' => 'รหัสผ่านใหม่'
            ],
            'user.confirm_new_password' => [
                'en' => 'Confirm New Password',
                'th' => 'ยืนยันรหัสผ่านใหม่'
            ]
        ];

        // Warehouse translations
        $warehouseTranslations = [
            'warehouse.new_transfer' => [
                'en' => 'New Transfer',
                'th' => 'โอนย้ายใหม่'
            ],
            'warehouse.all_warehouses' => [
                'en' => 'All Warehouses',
                'th' => 'คลังสินค้าทั้งหมด'
            ],
            'warehouse.active_only' => [
                'en' => 'Active Only',
                'th' => 'เฉพาะที่ใช้งาน'
            ],
            'warehouse.deactivated_only' => [
                'en' => 'Deactivated Only',
                'th' => 'เฉพาะที่ปิดใช้งาน'
            ],
            'warehouse.warehouse' => [
                'en' => 'Warehouse',
                'th' => 'คลังสินค้า'
            ],
            'warehouse.main_warehouse' => [
                'en' => 'Main Warehouse',
                'th' => 'คลังสินค้าหลัก'
            ],
            'warehouse.branch' => [
                'en' => 'Branch',
                'th' => 'สาขา'
            ],
            'warehouse.created' => [
                'en' => 'Created',
                'th' => 'สร้างเมื่อ'
            ],
            'warehouse.creator' => [
                'en' => 'Creator',
                'th' => 'ผู้สร้าง'
            ],
            'warehouse.add_new_warehouse' => [
                'en' => 'Add new warehouse',
                'th' => 'เพิ่มคลังสินค้าใหม่'
            ],
            'warehouse.check_stock_work_list' => [
                'en' => 'Check stock work list',
                'th' => 'รายการงานตรวจสอบสต็อก'
            ],
            'warehouse.new_check_stock' => [
                'en' => 'New Check Stock',
                'th' => 'ตรวจสอบสต็อกใหม่'
            ],
            'warehouse.all_reports' => [
                'en' => 'All Reports',
                'th' => 'รายงานทั้งหมด'
            ],
            'warehouse.in_process' => [
                'en' => 'In Process',
                'th' => 'กำลังดำเนินการ'
            ],
            'warehouse.completed' => [
                'en' => 'Completed',
                'th' => 'เสร็จสิ้น'
            ],
            'warehouse.expired' => [
                'en' => 'Expired',
                'th' => 'หมดอายุ'
            ],
            'warehouse.work_list' => [
                'en' => 'Work list',
                'th' => 'รายการงาน'
            ],
            'warehouse.work_date' => [
                'en' => 'work date',
                'th' => 'วันที่ทำงาน'
            ],
            'warehouse.expire_date' => [
                'en' => 'expire date',
                'th' => 'วันที่หมดอายุ'
            ],
            'warehouse.no_check_stock_reports_found' => [
                'en' => 'No check stock reports found',
                'th' => 'ไม่พบรายงานตรวจสอบสต็อก'
            ],
            'branch.branch_name' => [
                'en' => 'Branch Name',
                'th' => 'ชื่อสาขา'
            ],
            'branch.branch_no' => [
                'en' => 'Branch No.',
                'th' => 'เลขที่สาขา'
            ],
            'branch.edit_branch' => [
                'en' => 'Edit Branch',
                'th' => 'แก้ไขสาขา'
            ],
            'branch.delete_branch' => [
                'en' => 'Delete Branch',
                'th' => 'ลบสาขา'
            ],
            'branch.billing_address' => [
                'en' => 'Billing Address',
                'th' => 'ที่อยู่ออกใบเสร็จ'
            ],
            'common.status' => [
                'en' => 'Status',
                'th' => 'สถานะ'
            ],
            'common.active' => [
                'en' => 'Active',
                'th' => 'ใช้งาน'
            ],
            'common.inactive' => [
                'en' => 'Inactive',
                'th' => 'ไม่ใช้งาน'
            ],
            'common.action' => [
                'en' => 'Action',
                'th' => 'การดำเนินการ'
            ],
            'common.baht' => [
                'en' => 'Baht',
                'th' => 'บาท'
            ],
            'common.actions' => [
                'en' => 'Actions',
                'th' => 'การดำเนินการ'
            ],
            'warehouse.warehouse_name' => [
                'en' => 'Warehouse Name',
                'th' => 'ชื่อคลังสินค้า'
            ],
            'warehouse.average_remaining_price' => [
                'en' => 'Average Remaining Price',
                'th' => 'ราคาเฉลี่ยคงเหลือ'
            ],
            'warehouse.no_warehouses_found_for_branch' => [
                'en' => 'No warehouses found for this branch',
                'th' => 'ไม่พบคลังสินค้าสำหรับสาขานี้'
            ],
            'user.avatar' => [
                'en' => 'Avatar',
                'th' => 'รูปโปรไฟล์'
            ],
            'user.name' => [
                'en' => 'Name',
                'th' => 'ชื่อ'
            ],
            'user.user_management_coming_soon' => [
                'en' => 'User management for this branch - Coming soon',
                'th' => 'การจัดการผู้ใช้สำหรับสาขานี้ - เร็วๆ นี้'
            ],
            'setting.payment_information_coming_soon' => [
                'en' => 'Payment information for this branch - Coming soon',
                'th' => 'ข้อมูลการชำระเงินสำหรับสาขานี้ - เร็วๆ นี้'
            ],
            'warehouse.operator' => [
                'en' => 'Operator',
                'th' => 'ผู้ดำเนินการ'
            ],
            'warehouse.duration' => [
                'en' => 'Duration',
                'th' => 'ระยะเวลา'
            ],
            'warehouse.job_status' => [
                'en' => 'Job Status',
                'th' => 'สถานะงาน'
            ],
            'warehouse.last_count_date' => [
                'en' => 'Last Count Date',
                'th' => 'ตรวจนับครั้งล่าสุด'
            ],
            'warehouse.close_report' => [
                'en' => 'Close Report',
                'th' => 'ปิดรายงาน'
            ],
            'warehouse.reopen_report' => [
                'en' => 'Reopen Report',
                'th' => 'เปิดรายงานใหม่'
            ],
            'product.product_code' => [
                'en' => 'Product Code',
                'th' => 'รหัสสินค้า'
            ],
            'warehouse.counted_system_quantity' => [
                'en' => 'Counted/System Quantity',
                'th' => 'จำนวนนับ/ ตามระบบ'
            ],
            'warehouse.count_result' => [
                'en' => 'Count Result',
                'th' => 'ผลการนับ'
            ],
            'warehouse.no_check_stock_details_found' => [
                'en' => 'No check stock details found',
                'th' => 'ไม่พบรายละเอียดการตรวจสอบสต็อก'
            ],
            'warehouse.no_check_stock_report_selected' => [
                'en' => 'No Check Stock Report Selected',
                'th' => 'ไม่ได้เลือกรายงานตรวจสอบสต็อก'
            ],
            'warehouse.select_check_stock_report_from_list' => [
                'en' => 'Please select a check stock report from the list to view details',
                'th' => 'กรุณาเลือกรายงานตรวจสอบสต็อกจากรายการเพื่อดูรายละเอียด'
            ],
            'warehouse.create_new_check_stock_report' => [
                'en' => 'Create New Check Stock Report',
                'th' => 'สร้างรายงานตรวจสอบสต็อกใหม่'
            ],
            'warehouse.select_warehouse' => [
                'en' => 'Select Warehouse',
                'th' => 'เลือกคลังสินค้า'
            ],
            'user.user' => [
                'en' => 'User',
                'th' => 'ผู้ใช้'
            ],
            'user.select_user' => [
                'en' => 'Select User',
                'th' => 'เลือกผู้ใช้'
            ],
            'warehouse.check_date' => [
                'en' => 'Check Date',
                'th' => 'วันที่ตรวจสอบ'
            ],
            'warehouse.optional_description_check_stock' => [
                'en' => 'Optional description for this check stock report',
                'th' => 'คำอธิบายเพิ่มเติมสำหรับรายงานตรวจสอบสต็อกนี้'
            ],
            'warehouse.products_to_check' => [
                'en' => 'Products to Check',
                'th' => 'สินค้าที่จะตรวจสอบ'
            ],
            'product.product' => [
                'en' => 'Product',
                'th' => 'สินค้า'
            ],
            'product.select_product' => [
                'en' => 'Select Product',
                'th' => 'เลือกสินค้า'
            ],
            'product.add_product' => [
                'en' => 'Add Product',
                'th' => 'เพิ่มสินค้า'
            ],
            'product.sku' => [
                'en' => 'SKU',
                'th' => 'รหัสสินค้า'
            ],
            'warehouse.no_products_added_yet' => [
                'en' => 'No products added yet. Please add products to create the check stock report.',
                'th' => 'ยังไม่ได้เพิ่มสินค้า กรุณาเพิ่มสินค้าเพื่อสร้างรายงานตรวจสอบสต็อก'
            ],
            'warehouse.create_check_stock_report' => [
                'en' => 'Create Check Stock Report',
                'th' => 'สร้างรายงานตรวจสอบสต็อก'
            ],
            'warehouse.no_transfer_selected' => [
                'en' => 'No Transfer Selected',
                'th' => 'ยังไม่ได้เลือกการโอนย้าย'
            ],
            'warehouse.select_transfer_slip_from_list' => [
                'en' => 'Please select a transfer slip from the list to view details',
                'th' => 'กรุณาเลือกใบโอนย้ายจากรายการเพื่อดูรายละเอียด'
            ],
            'warehouse.select_warehouse_to_view_details' => [
                'en' => 'Select a warehouse to view details',
                'th' => 'เลือกคลังสินค้าเพื่อดูรายละเอียด'
            ],
            'warehouse.choose_warehouse_from_list' => [
                'en' => 'Choose a warehouse from the list to see its information',
                'th' => 'เลือกคลังสินค้าจากรายการเพื่อดูข้อมูล'
            ],
            'warehouse.stock_operations' => [
                'en' => 'Stock Operations',
                'th' => 'การดำเนินการสต็อก'
            ],
            'warehouse.stock_in' => [
                'en' => 'Stock In',
                'th' => 'รับเข้าสต็อก'
            ],
            'warehouse.stock_out' => [
                'en' => 'Stock Out',
                'th' => 'ส่งออกสต็อก'
            ],
            'warehouse.adjustment' => [
                'en' => 'Adjustment',
                'th' => 'ปรับปรุง'
            ],
            'warehouse.recent_transactions' => [
                'en' => 'Recent Transactions',
                'th' => 'รายการล่าสุด'
            ],
            'warehouse.latest_stock_movements' => [
                'en' => 'Latest stock movements',
                'th' => 'การเคลื่อนไหวสต็อกล่าสุด'
            ],
            'warehouse.no_recent_transactions' => [
                'en' => 'No recent transactions',
                'th' => 'ไม่มีรายการล่าสุด'
            ],
            'product.current_stock' => [
                'en' => 'Current Stock',
                'th' => 'สต็อกปัจจุบัน'
            ],
            'product.detail_notes' => [
                'en' => 'Detail/Notes',
                'th' => 'รายละเอียด/หมายเหตุ'
            ],
            'product.optional_notes' => [
                'en' => 'Optional notes',
                'th' => 'หมายเหตุเพิ่มเติม'
            ],
            'product.new_stock_balance' => [
                'en' => 'New Stock Balance',
                'th' => 'ยอดสต็อกใหม่'
            ],
            'common.in' => [
                'en' => 'in',
                'th' => 'ใน'
            ],
            'common.processing' => [
                'en' => 'Processing...',
                'th' => 'กำลังประมวลผล...'
            ],
            'product.search_for_product' => [
                'en' => 'Search for a product...',
                'th' => 'ค้นหาสินค้า...'
            ],
            'warehouse.stock_operations_management' => [
                'en' => 'Stock Operations Management',
                'th' => 'การจัดการการดำเนินการสต็อก'
            ],
            'warehouse.stock_operations_description' => [
                'en' => 'Manage your inventory with stock-in, stock-out, and adjustment operations. All operations are tracked and logged for audit purposes.',
                'th' => 'จัดการสินค้าคงคลังของคุณด้วยการรับเข้า, ส่งออก และการปรับปรุงสต็อก การดำเนินการทั้งหมดจะถูกติดตามและบันทึกเพื่อวัตถุประสงค์การตรวจสอบ'
            ],
            'warehouse.operation_guide' => [
                'en' => 'Operation Guide',
                'th' => 'คู่มือการดำเนินการ'
            ],
            'warehouse.stock_in' => [
                'en' => 'Stock In',
                'th' => 'รับเข้าสต็อก'
            ],
            'warehouse.stock_in_description' => [
                'en' => 'Add inventory to your warehouse. Use this for:',
                'th' => 'เพิ่มสินค้าคงคลังเข้าคลังสินค้าของคุณ ใช้สำหรับ:'
            ],
            'warehouse.new_purchases' => [
                'en' => 'New purchases',
                'th' => 'การซื้อใหม่'
            ],
            'warehouse.returns_from_customers' => [
                'en' => 'Returns from customers',
                'th' => 'การคืนจากลูกค้า'
            ],
            'warehouse.production_outputs' => [
                'en' => 'Production outputs',
                'th' => 'ผลผลิต'
            ],
            'warehouse.initial_stock_setup' => [
                'en' => 'Initial stock setup',
                'th' => 'การตั้งค่าสต็อกเริ่มต้น'
            ],
            'warehouse.stock_out' => [
                'en' => 'Stock Out',
                'th' => 'ส่งออกสต็อก'
            ],
            'warehouse.stock_out_description' => [
                'en' => 'Remove inventory from your warehouse. Use this for:',
                'th' => 'นำสินค้าคงคลังออกจากคลังสินค้าของคุณ ใช้สำหรับ:'
            ],
            'warehouse.sales_to_customers' => [
                'en' => 'Sales to customers',
                'th' => 'การขายให้ลูกค้า'
            ],
            'warehouse.returns_to_suppliers' => [
                'en' => 'Returns to suppliers',
                'th' => 'การคืนให้ผู้จัดจำหน่าย'
            ],
            'warehouse.internal_consumption' => [
                'en' => 'Internal consumption',
                'th' => 'การบริโภคภายใน'
            ],
            'warehouse.damaged_lost_items' => [
                'en' => 'Damaged/lost items',
                'th' => 'สินค้าเสียหาย/สูญหาย'
            ],
            'warehouse.stock_adjustment' => [
                'en' => 'Stock Adjustment',
                'th' => 'การปรับปรุงสต็อก'
            ],
            'warehouse.stock_adjustment_description' => [
                'en' => 'Correct inventory discrepancies. Use this for:',
                'th' => 'แก้ไขความแตกต่างของสินค้าคงคลัง ใช้สำหรับ:'
            ],
            'warehouse.physical_count_corrections' => [
                'en' => 'Physical count corrections',
                'th' => 'การแก้ไขการนับจริง'
            ],
            'warehouse.system_error_corrections' => [
                'en' => 'System error corrections',
                'th' => 'การแก้ไขข้อผิดพลาดของระบบ'
            ],
            'warehouse.shrinkage_adjustments' => [
                'en' => 'Shrinkage adjustments',
                'th' => 'การปรับปรุงการหดตัว'
            ],
            'warehouse.quality_control_rejects' => [
                'en' => 'Quality control rejects',
                'th' => 'การปฏิเสธการควบคุมคุณภาพ'
            ]
        ];

        // Category translations
        $categoryTranslations = [
            'category.add_new_category' => [
                'en' => 'Add new category',
                'th' => 'เพิ่มหมวดหมู่ใหม่'
            ]
        ];

        // Authentication translations
        $authTranslations = [
            'auth.sign_in_to_account' => [
                'en' => 'Sign in to your account',
                'th' => 'เข้าสู่ระบบบัญชีของคุณ'
            ],
            'auth.username' => [
                'en' => 'username',
                'th' => 'ชื่อผู้ใช้'
            ],
            'auth.password' => [
                'en' => 'Password',
                'th' => 'รหัสผ่าน'
            ],
            'auth.remember_me' => [
                'en' => 'Remember me',
                'th' => 'จดจำฉัน'
            ],
            'auth.forgot_password' => [
                'en' => 'Forgot password?',
                'th' => 'ลืมรหัสผ่าน?'
            ],
            'auth.sign_in' => [
                'en' => 'Sign in',
                'th' => 'เข้าสู่ระบบ'
            ],
            'auth.create_new_account' => [
                'en' => 'Create new account',
                'th' => 'สร้างบัญชีใหม่'
            ],
            'auth.name' => [
                'en' => 'Name',
                'th' => 'ชื่อ'
            ],
            'auth.email' => [
                'en' => 'Email',
                'th' => 'อีเมล'
            ],
            'auth.confirm_password' => [
                'en' => 'Confirm password',
                'th' => 'ยืนยันรหัสผ่าน'
            ],
            'auth.mail_account_details' => [
                'en' => 'Mail me my account details',
                'th' => 'ส่งรายละเอียดบัญชีให้ฉันทางอีเมล'
            ],
            'auth.accept_terms' => [
                'en' => 'Accept terms & conditions',
                'th' => 'ยอมรับข้อกำหนดและเงื่อนไข'
            ],
            'auth.register_now' => [
                'en' => 'Register now',
                'th' => 'สมัครสมาชิกตอนนี้'
            ]
        ];

        // Setting translations
        $settingTranslations = [
            'setting.company_setup' => [
                'en' => 'Company Setup',
                'th' => 'การตั้งค่าบริษัท'
            ],
            'setting.detail' => [
                'en' => 'Detail',
                'th' => 'รายละเอียด'
            ],
            'setting.tax_setup' => [
                'en' => 'Tax Setup',
                'th' => 'การตั้งค่าภาษี'
            ],
            'setting.payment_setup' => [
                'en' => 'Payment Setup',
                'th' => 'การตั้งค่าการชำระเงิน'
            ],
            'setting.payment' => [
                'en' => 'Payment',
                'th' => 'การชำระเงิน'
            ],
            'setting.branch_details' => [
                'en' => 'Branch details',
                'th' => 'รายละเอียดสาขา'
            ],
            'setting.head_office' => [
                'en' => 'Head Office',
                'th' => 'สำนักงานใหญ่'
            ],
            'setting.branch_name' => [
                'en' => 'Branch Name',
                'th' => 'ชื่อสาขา'
            ],
            'setting.branch_no' => [
                'en' => 'Branch No.',
                'th' => 'หมายเลขสาขา'
            ],
            'setting.address' => [
                'en' => 'Address',
                'th' => 'ที่อยู่'
            ],
            'setting.billing_address' => [
                'en' => 'Billing Address',
                'th' => 'ที่อยู่เรียกเก็บเงิน'
            ],
            'setting.no_active_head_office' => [
                'en' => 'No active head office found.',
                'th' => 'ไม่พบสำนักงานใหญ่ที่ใช้งาน'
            ],
            'setting.other_branches' => [
                'en' => 'Other Branches',
                'th' => 'สาขาอื่นๆ'
            ],
            'setting.branch_code' => [
                'en' => 'Branch Code',
                'th' => 'รหัสสาขา'
            ],
            'setting.branch_name_th' => [
                'en' => 'Branch Name (TH)',
                'th' => 'ชื่อสาขา (ไทย)'
            ],
            'setting.branch_name_en' => [
                'en' => 'Branch Name (EN)',
                'th' => 'ชื่อสาขา (อังกฤษ)'
            ],
            'setting.address_th' => [
                'en' => 'Address (TH)',
                'th' => 'ที่อยู่ (ไทย)'
            ],
            'setting.address_en' => [
                'en' => 'Address (EN)',
                'th' => 'ที่อยู่ (อังกฤษ)'
            ],
            'setting.phone' => [
                'en' => 'Phone',
                'th' => 'โทรศัพท์'
            ],
            'setting.mobile' => [
                'en' => 'Mobile',
                'th' => 'มือถือ'
            ],
            'setting.fax' => [
                'en' => 'Fax',
                'th' => 'แฟกซ์'
            ],
            'setting.email' => [
                'en' => 'Email',
                'th' => 'อีเมล'
            ],
            'setting.website' => [
                'en' => 'Website',
                'th' => 'เว็บไซต์'
            ],
            'setting.vat_percent' => [
                'en' => 'ภาษีมูลค่าเพิ่ม (% VAT)',
                'th' => 'ภาษีมูลค่าเพิ่ม (% VAT)'
            ],
            'setting.withholding_tax' => [
                'en' => 'ภาษีหัก ณ ที่จ่าย',
                'th' => 'ภาษีหัก ณ ที่จ่าย'
            ],
            'setting.currency' => [
                'en' => 'สกุลเงิน',
                'th' => 'สกุลเงิน'
            ],
            'setting.default_language' => [
                'en' => 'ภาษาหลัก',
                'th' => 'ภาษาหลัก'
            ],
            'setting.bank_account' => [
                'en' => 'บัญชีธนาคาร',
                'th' => 'บัญชีธนาคาร'
            ],
            'setting.cash' => [
                'en' => 'เงินสด',
                'th' => 'เงินสด'
            ],
            'setting.branch_updated_successfully' => [
                'en' => 'Branch updated successfully',
                'th' => 'อัปเดตสาขาสำเร็จ'
            ],
            'setting.error_updating_branch' => [
                'en' => 'Error updating branch',
                'th' => 'ข้อผิดพลาดในการอัปเดตสาขา'
            ],
            'setting.confirm_delete_branch' => [
                'en' => 'Are you sure you want to delete this branch?',
                'th' => 'คุณแน่ใจหรือไม่ว่าต้องการลบสาขานี้?'
            ],
            'setting.branch_deleted_successfully' => [
                'en' => 'Branch deleted successfully',
                'th' => 'ลบสาขาสำเร็จ'
            ],
            'setting.error_deleting_branch' => [
                'en' => 'Error deleting branch',
                'th' => 'ข้อผิดพลาดในการลบสาขา'
            ]
        ];

        // Combine all translations
        $allTranslations = array_merge(
            $sidebarTranslations,
            $commonTranslations,
            $profileTranslations,
            $dashboardTranslations,
            $productTranslations,
            $menuTranslations,
            $branchTranslations,
            $userTranslations,
            $warehouseTranslations,
            $settingTranslations,
            $authTranslations,
            $categoryTranslations
        );

        // Insert translations
        foreach ($allTranslations as $key => $translations) {
            foreach ($translations as $locale => $value) {
                $translationService->set($key, $locale, $value, 'default', "Translation for {$key}");
            }
        }

        $this->command->info('Translation seeder completed successfully!');
        $this->command->info('Created ' . count($allTranslations) . ' translation keys with Thai and English translations.');
    }
}
