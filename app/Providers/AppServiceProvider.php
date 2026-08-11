<?php

namespace App\Providers;

use App\Models\DomainRenewal;
use App\Observers\DomainRenewalObserver;
use App\Services\MenuService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        DomainRenewal::observe(DomainRenewalObserver::class);

        View::composer('layouts.partials.sidebar', function ($view) {
            $view->with('adminMenu', MenuService::items());
        });
    }
}
