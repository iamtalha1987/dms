<?php

use App\Models\Setting;

if (! function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        return (string) Setting::get('currency_symbol', '$');
    }
}

if (! function_exists('format_money')) {
    function format_money(float|string|int|null $amount): string
    {
        return currency_symbol().number_format((float) ($amount ?? 0), 2);
    }
}
