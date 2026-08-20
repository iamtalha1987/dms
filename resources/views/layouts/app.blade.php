<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DMS') }}@isset($title) — {{ $title }}@endisset</title>

        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        {{-- Critical layout styles (works even if Vite CSS fails to load) --}}
        <style>
            [x-cloak] { display: none !important; }
            *, *::before, *::after { box-sizing: border-box; }
            body { margin: 0; font-family: Figtree, ui-sans-serif, system-ui, sans-serif; background: #f1f5f9; color: #0f172a; }
            .dms-layout { display: flex; min-height: 100vh; }
            .dms-sidebar {
                width: 16rem; flex-shrink: 0; background: #0f172a; color: #e2e8f0;
                position: fixed; inset: 0 auto 0 0; z-index: 50;
                display: flex; flex-direction: column;
                overflow-y: auto;
                transition: transform 0.2s ease;
            }
            .dms-sidebar nav a {
                display: flex; align-items: center; gap: 0.75rem;
                padding: 0.625rem 0.75rem; margin-bottom: 0.25rem;
                border-radius: 0.5rem; text-decoration: none; font-size: 0.875rem; font-weight: 500;
                color: #cbd5e1;
            }
            .dms-sidebar nav a:hover { background: #1e293b; color: #fff; }
            .dms-sidebar nav a.active { background: #4f46e5; color: #fff; }
            .dms-main { flex: 1; margin-left: 16rem; min-height: 100vh; display: flex; flex-direction: column; min-width: 0; }
            .dms-topbar {
                position: sticky; top: 0; z-index: 30; height: 4rem;
                display: flex; align-items: center; gap: 1rem;
                padding: 0 1.5rem; background: #fff; border-bottom: 1px solid #e2e8f0;
                box-shadow: 0 1px 2px rgb(0 0 0 / 0.05);
            }
            .dms-topbar-title { flex: 1; font-size: 1.125rem; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .dms-content { flex: 1; padding: 1.5rem; }
            .dms-menu-btn { display: none; background: none; border: none; padding: 0.5rem; cursor: pointer; color: #64748b; }
            .dms-backdrop { display: none; position: fixed; inset: 0; background: rgb(15 23 42 / 0.5); z-index: 40; }
            @media (max-width: 1023px) {
                .dms-sidebar { transform: translateX(-100%); }
                .dms-sidebar.is-open { transform: translateX(0); }
                .dms-main { margin-left: 0; }
                .dms-menu-btn { display: inline-flex; }
                .dms-backdrop.is-open { display: block; }
            }
        </style>

        @include('layouts.partials.assets')
    </head>
    <body>
        <div x-data="{ sidebarOpen: false }" class="dms-layout" @keydown.escape.window="sidebarOpen = false">
            <div
                class="dms-backdrop"
                :class="{ 'is-open': sidebarOpen }"
                @click="sidebarOpen = false"
                x-show="sidebarOpen"
                x-cloak
            ></div>

            @include('layouts.partials.sidebar')

            <div class="dms-main">
                @include('layouts.partials.topbar')

                <main class="dms-content">
                    @include('layouts.partials.flash')
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
