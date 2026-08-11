<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'expiry_alert_days' => '30',
            'currency_symbol' => '$',
            'notify_admin_enabled' => '1',
            'notify_client_enabled' => '1',
            'admin_notification_email' => config('mail.from.address', 'admin@example.com'),
        ];

        foreach ($defaults as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
