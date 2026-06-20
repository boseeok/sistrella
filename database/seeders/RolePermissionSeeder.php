<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Seeds the RBAC matrix: the granular permissions used by the admin routes
 * and the system roles (admin, manager, staff, customer) that bundle them.
 *
 * The "admin" role is a super-user (bypasses granular checks in HasRoles),
 * but we still attach every permission so the role reads correctly in the UI.
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * group => [ name => display_name ]
     */
    private array $permissions = [
        'Dashboard' => [
            'dashboard.access' => 'Access admin panel',
        ],
        'Orders' => [
            'orders.view'   => 'View orders',
            'orders.manage' => 'Manage orders',
        ],
        'Payments' => [
            'payments.manage' => 'Verify & manage payments',
        ],
        'Catalogue' => [
            'products.manage'   => 'Manage products',
            'categories.manage' => 'Manage categories',
            'inventory.manage'  => 'Manage inventory',
        ],
        'Marketing' => [
            'coupons.manage'   => 'Manage coupons',
            'marketing.manage' => 'Manage banners & marketing',
        ],
        'Customers' => [
            'customers.view'   => 'View customers',
            'customers.manage' => 'Manage customers',
        ],
        'Custom Requests' => [
            'custom.manage' => 'Manage custom requests',
        ],
        'Reports' => [
            'reports.view' => 'View reports',
        ],
        'Settings' => [
            'settings.manage' => 'Manage store settings',
            'roles.manage'    => 'Manage roles & staff',
        ],
    ];

    public function run(): void
    {
        // 1. Permissions
        foreach ($this->permissions as $group => $items) {
            foreach ($items as $name => $display) {
                Permission::updateOrCreate(
                    ['name' => $name],
                    ['display_name' => $display, 'group' => $group],
                );
            }
        }

        $all = Permission::pluck('name')->all();

        // 2. Roles
        $admin = Role::updateOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Administrator', 'description' => 'Full access to everything.', 'is_system' => true],
        );

        $manager = Role::updateOrCreate(
            ['name' => 'manager'],
            ['display_name' => 'Store Manager', 'description' => 'Runs day-to-day store operations.', 'is_system' => true],
        );

        $staff = Role::updateOrCreate(
            ['name' => 'staff'],
            ['display_name' => 'Staff', 'description' => 'Limited operational access.', 'is_system' => true],
        );

        $customer = Role::updateOrCreate(
            ['name' => 'customer'],
            ['display_name' => 'Customer', 'description' => 'Shop customer (no admin access).', 'is_system' => true],
        );

        // 3. Attach permissions
        $admin->syncPermissions($all);

        $manager->syncPermissions(array_values(array_diff($all, [
            'roles.manage', // managers cannot administer roles/staff
        ])));

        $staff->syncPermissions([
            'dashboard.access',
            'orders.view', 'orders.manage',
            'payments.manage',
            'inventory.manage',
            'custom.manage',
        ]);

        // Customers have no admin permissions.
        $customer->syncPermissions([]);
    }
}
