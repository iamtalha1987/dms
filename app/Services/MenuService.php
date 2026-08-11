<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class MenuService
{
    public static function items(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        return collect(config('admin_menu', []))
            ->filter(fn (array $item) => $user->can($item['permission']))
            ->values()
            ->all();
    }
}
