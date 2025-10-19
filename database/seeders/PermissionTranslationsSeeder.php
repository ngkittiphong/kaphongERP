<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\TranslationService;

class PermissionTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = [
            'permissions.access_control' => [
                'en' => 'Access Control',
                'th' => 'การควบคุมสิทธิ์',
            ],
            'permissions.select_user_hint' => [
                'en' => 'Select a user to manage roles and permissions.',
                'th' => 'เลือกผู้ใช้เพื่อจัดการกลุ่มและสิทธิ์การเข้าถึง',
            ],
            'permissions.user_label' => [
                'en' => 'User:',
                'th' => 'ผู้ใช้:',
            ],
            'permissions.roles' => [
                'en' => 'Roles',
                'th' => 'กลุ่มผู้ใช้',
            ],
            'permissions.assign_roles_hint' => [
                'en' => 'Assign one or more roles to this user.',
                'th' => 'กำหนดหนึ่งหรือหลายกลุ่มให้ผู้ใช้นี้',
            ],
            'permissions.current_roles' => [
                'en' => 'Current roles:',
                'th' => 'กลุ่มปัจจุบัน:',
            ],
            'permissions.direct_permissions' => [
                'en' => 'Direct Permissions',
                'th' => 'สิทธิ์เฉพาะ',
            ],
            'permissions.assign_permissions_hint' => [
                'en' => 'Grant or revoke permissions outside of role membership.',
                'th' => 'ให้หรือยกเลิกสิทธิ์นอกเหนือจากกลุ่มที่สังกัด',
            ],
            'permissions.current_permissions' => [
                'en' => 'Current permissions:',
                'th' => 'สิทธิ์ปัจจุบัน:',
            ],
            'permissions.none' => [
                'en' => 'None',
                'th' => 'ไม่มี',
            ],
            'permissions.multi_select_help' => [
                'en' => 'Hold Ctrl (Cmd on Mac) to select or unselect multiple roles.',
                'th' => 'กดปุ่ม Ctrl (Cmd บน Mac) เพื่อเลือกหรือยกเลิกหลายรายการ',
            ],
            'permissions.multi_select_permissions_help' => [
                'en' => 'Hold Ctrl (Cmd on Mac) to select or unselect multiple permissions.',
                'th' => 'กดปุ่ม Ctrl (Cmd บน Mac) เพื่อเลือกหรือยกเลิกสิทธิ์หลายรายการ',
            ],
            'permissions.role_name' => [
                'en' => 'Role name',
                'th' => 'ชื่อกลุ่ม',
            ],
            'permissions.role_name_placeholder' => [
                'en' => 'Enter role name',
                'th' => 'ระบุชื่อกลุ่ม',
            ],
            'permissions.permissions' => [
                'en' => 'Permissions',
                'th' => 'สิทธิ์',
            ],
            'permissions.select_permissions_hint' => [
                'en' => 'Tick the permissions this role should grant.',
                'th' => 'เลือกสิทธิ์ที่กลุ่มนี้จะได้รับ',
            ],
            'permissions.update_role' => [
                'en' => 'Update Role',
                'th' => 'บันทึกกลุ่ม',
            ],
            'permissions.create_role' => [
                'en' => 'Create Role',
                'th' => 'สร้างกลุ่ม',
            ],
            'permissions.delete_role_warning' => [
                'en' => 'Are you sure you want to delete this role? This action cannot be undone.',
                'th' => 'ต้องการลบกลุ่มนี้หรือไม่? การดำเนินการนี้ไม่สามารถย้อนกลับได้',
            ],
            'permissions.confirm_delete' => [
                'en' => 'Yes, delete',
                'th' => 'ยืนยันการลบ',
            ],
            'permissions.existing_roles' => [
                'en' => 'Existing Roles',
                'th' => 'กลุ่มที่มีอยู่',
            ],
            'permissions.permissions_count_label' => [
                'en' => 'permission(s)',
                'th' => 'สิทธิ์',
            ],
            'permissions.no_roles' => [
                'en' => 'No roles defined yet.',
                'th' => 'ยังไม่มีกลุ่มถูกสร้าง',
            ],
            'permissions.user_role_assignments' => [
                'en' => 'User Role Assignments',
                'th' => 'กำหนดกลุ่มให้ผู้ใช้',
            ],
            'permissions.username' => [
                'en' => 'Username',
                'th' => 'ชื่อผู้ใช้',
            ],
            'permissions.email' => [
                'en' => 'Email',
                'th' => 'อีเมล',
            ],
            'permissions.no_permission_title' => [
                'en' => 'Access Restricted',
                'th' => 'ไม่สามารถเข้าถึงได้',
            ],
            'permissions.no_permission_message' => [
                'en' => 'Your account does not have the permissions required to view this page.',
                'th' => 'บัญชีของคุณยังไม่มีสิทธิ์สำหรับหน้าเพจนี้',
            ],
            'permissions.no_permission_hint' => [
                'en' => 'Please contact an administrator if you believe this is an error.',
                'th' => 'กรุณาติดต่อผู้ดูแลระบบหากคิดว่าเกิดความผิดพลาด',
            ],
            'permissions.logout_button' => [
                'en' => 'Sign out',
                'th' => 'ออกจากระบบ',
            ],
        ];

        $translationService = app(TranslationService::class);

        foreach ($translations as $key => $locales) {
            foreach ($locales as $locale => $value) {
                $translationService->set($key, $locale, $value, 'default', "Permission translation for {$key}");
            }
        }
    }
}
