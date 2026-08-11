<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings.view')->only(['edit']);
        $this->middleware('permission:settings.manage')->only(['update']);
    }

    public function edit(): View
    {
        $settings = [
            'expiry_alert_days' => Setting::get('expiry_alert_days', '30'),
            'notify_admin_enabled' => Setting::get('notify_admin_enabled', '1'),
            'notify_client_enabled' => Setting::get('notify_client_enabled', '1'),
            'admin_notification_email' => Setting::get('admin_notification_email', config('mail.from.address')),
            'currency_symbol' => Setting::get('currency_symbol', '$'),
        ];

        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'expiry_alert_days' => ['required', 'integer', 'min:1', 'max:365'],
            'notify_admin_enabled' => ['boolean'],
            'notify_client_enabled' => ['boolean'],
            'admin_notification_email' => ['required', 'email'],
            'currency_symbol' => ['required', 'string', 'max:5'],
        ]);

        Setting::set('expiry_alert_days', (string) $validated['expiry_alert_days']);
        Setting::set('currency_symbol', $validated['currency_symbol']);
        Setting::set('notify_admin_enabled', $request->boolean('notify_admin_enabled') ? '1' : '0');
        Setting::set('notify_client_enabled', $request->boolean('notify_client_enabled') ? '1' : '0');
        Setting::set('admin_notification_email', $validated['admin_notification_email']);

        return back()->with('success', 'Settings saved.');
    }
}
