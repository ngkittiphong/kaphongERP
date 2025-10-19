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
            'super_admin' => $permissions, // Full admin access (was admin1)
            'product_admin' => [ // Was admin2
                'menu.dashboard',
                'menu.products',
                'menu.warehouse',
                'menu.branch',
            ],
            'warehouse_admin' => [ // Was admin3
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

        // Assign roles to admin users
        $adminUsers = User::whereIn('username', ['admin1', 'admin2', 'admin3'])
            ->get()
            ->keyBy('username');

        // Assign super_admin role to admin1 user
        if (isset($adminUsers['admin1'])) {
            $adminUsers['admin1']->syncRoles(['super_admin']);
        }

        // Assign product_admin role to admin2 user
        if (isset($adminUsers['admin2'])) {
            $adminUsers['admin2']->syncRoles(['product_admin']);
        }

        // Assign warehouse_admin role to admin3 user
        if (isset($adminUsers['admin3'])) {
            $adminUsers['admin3']->syncRoles(['warehouse_admin']);
        }

        // Assign super_admin role to all users with Admin user type
        $adminTypeUsers = User::whereHas('type', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        foreach ($adminTypeUsers as $user) {
            // Only assign if user doesn't already have a specific admin role
            if (!$user->hasAnyRole(['super_admin', 'product_admin', 'warehouse_admin'])) {
                $user->syncRoles(['super_admin']);
            }
        }
    }
}
