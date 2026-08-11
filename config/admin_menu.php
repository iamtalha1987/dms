<?php

return [
    ['label' => 'Dashboard', 'route' => 'dashboard', 'permission' => 'dashboard.view', 'pattern' => 'dashboard'],
    ['label' => 'Clients', 'route' => 'clients.index', 'permission' => 'clients.view', 'pattern' => 'clients.*'],
    ['label' => 'Domains / Projects', 'route' => 'domains.index', 'permission' => 'domains.view', 'pattern' => 'domains.*'],
    ['label' => 'Renewal History', 'route' => 'renewals.index', 'permission' => 'renewals.view', 'pattern' => 'renewals.*'],
    ['label' => 'Expiry Alerts', 'route' => 'expiry.index', 'permission' => 'expiry.view', 'pattern' => 'expiry.*'],
    ['label' => 'Reports', 'route' => 'reports.index', 'permission' => 'reports.view', 'pattern' => 'reports.*'],
    ['label' => 'Suppliers', 'route' => 'suppliers.index', 'permission' => 'suppliers.view', 'pattern' => 'suppliers.*'],
    ['label' => 'Users', 'route' => 'users.index', 'permission' => 'users.view', 'pattern' => 'users.*'],
    ['label' => 'Roles', 'route' => 'roles.index', 'permission' => 'roles.view', 'pattern' => 'roles.*'],
    ['label' => 'Permissions', 'route' => 'permissions.index', 'permission' => 'permissions.view', 'pattern' => 'permissions.*'],
    ['label' => 'Settings', 'route' => 'settings.edit', 'permission' => 'settings.view', 'pattern' => 'settings.*'],
];
