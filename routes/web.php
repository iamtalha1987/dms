<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\DomainRenewalController;
use App\Http\Controllers\ExpiryAlertController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    Route::resource('clients', ClientController::class);

    Route::resource('domains', DomainController::class);
    Route::get('domains/{domain}/renewals', [DomainRenewalController::class, 'index'])->name('domains.renewals.index');
    Route::get('domains/{domain}/renewals/create', [DomainRenewalController::class, 'create'])->name('domains.renewals.create');
    Route::post('domains/{domain}/renewals', [DomainRenewalController::class, 'store'])->name('domains.renewals.store');

    Route::get('renewals', [DomainRenewalController::class, 'globalIndex'])->name('renewals.index');
    Route::get('renewals/{renewal}/edit', [DomainRenewalController::class, 'edit'])->name('renewals.edit');
    Route::put('renewals/{renewal}', [DomainRenewalController::class, 'update'])->name('renewals.update');
    Route::delete('renewals/{renewal}', [DomainRenewalController::class, 'destroy'])->name('renewals.destroy');

    Route::get('expiry-alerts', [ExpiryAlertController::class, 'index'])->name('expiry.index');
    Route::post('expiry-alerts/{domain}/mark-notified', [ExpiryAlertController::class, 'markNotified'])->name('expiry.mark-notified');
    Route::post('expiry-alerts/{domain}/notify', [NotificationController::class, 'send'])->name('expiry.notify');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{type}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('reports/{type}/export', [ExportController::class, 'export'])->name('reports.export');

    Route::resource('suppliers', SupplierController::class)->except(['show']);

    Route::resource('users', UserController::class)->except(['show']);
    Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

    Route::resource('roles', RoleController::class)->except(['show']);
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::put('permissions', [PermissionController::class, 'update'])->name('permissions.update');

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
