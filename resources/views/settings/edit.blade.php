<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">System Settings</h2></x-slot>
    <form method="POST" action="{{ route('settings.update') }}" class="bg-white rounded-lg shadow-sm p-6 max-w-2xl space-y-4">
        @csrf @method('PUT')
        <div>
            <x-input-label for="expiry_alert_days" value="Default expiry alert window (days)" />
            <x-text-input id="expiry_alert_days" name="expiry_alert_days" type="number" class="block mt-1 w-full" :value="old('expiry_alert_days', $settings['expiry_alert_days'])" required />
        </div>
        <div>
            <x-input-label for="currency_symbol" value="Currency symbol" />
            <x-text-input id="currency_symbol" name="currency_symbol" class="block mt-1 w-full max-w-[8rem]" :value="old('currency_symbol', $settings['currency_symbol'])" required />
            <p class="mt-1 text-xs text-gray-500">Used for purchase and renewal amounts (e.g. $)</p>
        </div>
        <div>
            <x-input-label for="admin_notification_email" value="Admin notification email" />
            <x-text-input id="admin_notification_email" name="admin_notification_email" type="email" class="block mt-1 w-full" :value="old('admin_notification_email', $settings['admin_notification_email'])" required />
        </div>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="notify_admin_enabled" value="1" @checked(old('notify_admin_enabled', $settings['notify_admin_enabled']) == '1') />
            <span>Enable scheduled admin expiry emails</span>
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="notify_client_enabled" value="1" @checked(old('notify_client_enabled', $settings['notify_client_enabled']) == '1') />
            <span>Enable scheduled client expiry emails</span>
        </label>
        @can('settings.manage')
            <x-primary-button>Save Settings</x-primary-button>
        @endcan
    </form>
</x-app-layout>
