<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed the roles, permissions, and sample assignments.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'menu.dashboard',
            'menu.products',
            'menu.user_management',
            'menu.user_permissions',
            'menu.pos',
            'menu.branch',
            'menu.warehouse',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        $roleDefinitions = [
            'super_admin' => $permissions,
            'product_admin' => [
                'menu.dashboard',
                'menu.products',
            ],
            'warehouse_admin' => [
                'menu.dashboard',
                'menu.warehouse',
            ],
        ];

        foreach ($roleDefinitions as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web']
            );

            $role->syncPermissions($rolePermissions);
        }

        $users = User::whereIn('username', array_keys($roleDefinitions))
            ->get()
            ->keyBy('username');

        foreach ($roleDefinitions as $roleName => $rolePermissions) {
            if (isset($users[$roleName])) {
                $users[$roleName]->syncRoles($roleName);
            }
        }
    }
}
