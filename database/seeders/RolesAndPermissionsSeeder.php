<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create Permissions
        $permissions = [
            'view-dashboard', 'view-sales-overview', 'view-inventory-summary', 'view-order-metrics', 'view-staff-activity', 'view-reports', 'manage-staff',
            'manage-products', 'manage-categories', 'manage-inventory', 'manage-promotions', 'manage-banners', 'view-orders', 'update-orders', 'cancel-orders', 'refund-orders', 'manage-users', 'upload-media'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        // Clear the cache before assigning permissions to roles
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Create Roles
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $staffRole = Role::create(['name' => 'staff', 'guard_name' => 'api']);

        // Assign Permissions to Roles
        $adminRole->givePermissionTo([
            'view-dashboard', 'view-sales-overview', 'view-inventory-summary', 'view-order-metrics', 'view-staff-activity', 'view-reports', 'manage-staff'
        ]);

        $staffRole->givePermissionTo([
            'manage-products', 'manage-categories', 'manage-inventory', 'manage-promotions', 'manage-banners', 'view-orders', 'update-orders', 'cancel-orders', 'refund-orders', 'manage-users', 'upload-media'
        ]);
    }
}
