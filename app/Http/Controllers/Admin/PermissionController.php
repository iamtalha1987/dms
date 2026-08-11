<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permissions.view')->only(['index']);
        $this->middleware('permission:permissions.manage')->only(['update']);
    }

    public function index(): View
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('admin.permissions.index', compact('roles', 'permissions'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'roles' => ['array'],
        ]);

        foreach ($validated['roles'] ?? [] as $roleId => $permissionNames) {
            $role = Role::find($roleId);
            if (! $role || $role->name === 'Super Admin') {
                continue;
            }
            $role->syncPermissions($permissionNames ?? []);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', 'Permissions updated successfully.');
    }
}
