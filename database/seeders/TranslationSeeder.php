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
            ]
        ];

        // Combine all translations
        $allTranslations = array_merge(
            $sidebarTranslations,
            $commonTranslations,
            $profileTranslations,
            $menuTranslations
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
