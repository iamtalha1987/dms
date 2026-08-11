<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'users.view', 'users.create', 'users.edit', 'users.delete', 'users.activate',
            'roles.view', 'roles.manage',
            'permissions.view', 'permissions.manage',
            'clients.view', 'clients.create', 'clients.edit', 'clients.delete',
            'domains.view', 'domains.create', 'domains.edit', 'domains.delete',
            'renewals.view', 'renewals.create', 'renewals.edit', 'renewals.delete',
            'expiry.view', 'expiry.notify', 'expiry.mark_notified',
            'reports.view', 'reports.export',
            'suppliers.view', 'suppliers.create', 'suppliers.edit', 'suppliers.delete',
            'settings.view', 'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $all = Permission::all();

        $superAdmin = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($all);

        $admin = Role::query()->firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions($all->whereNotIn('name', ['permissions.manage']));

        $manager = Role::query()->firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $manager->syncPermissions($all->whereIn('name', [
            'dashboard.view',
            'clients.view', 'clients.create', 'clients.edit', 'clients.delete',
            'domains.view', 'domains.create', 'domains.edit', 'domains.delete',
            'renewals.view', 'renewals.create', 'renewals.edit', 'renewals.delete',
            'expiry.view', 'expiry.notify', 'expiry.mark_notified',
            'reports.view', 'reports.export',
            'suppliers.view',
        ]));

        $viewer = Role::query()->firstOrCreate(['name' => 'Staff / Viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions($all->whereIn('name', [
            'dashboard.view',
            'clients.view',
            'domains.view',
            'renewals.view',
            'expiry.view',
            'reports.view',
            'suppliers.view',
        ]));
    }
}
