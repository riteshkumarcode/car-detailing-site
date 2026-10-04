<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'manage_settings',
            'manage_users',
            'view_revenue',
            'export_data',
            'manage_pricing',
            'manage_services',
            'manage_bookings',
            'manage_health_checks',
            'manage_invoices',
            'manage_customers',
            'manage_memberships',
            'manage_website_content',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Roles
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $ownerRole->syncPermissions($permissions);

        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $managerRole->syncPermissions([
            'view_revenue',
            'manage_services',
            'manage_bookings',
            'manage_health_checks',
            'manage_invoices',
            'manage_customers',
            'manage_memberships',
            'manage_website_content',
        ]);

        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staffRole->syncPermissions([
            'manage_bookings',
            'manage_health_checks',
            'manage_customers',
        ]);
    }
}
