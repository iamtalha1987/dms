<header class="dms-topbar">
    <button type="button" class="dms-menu-btn" @click="sidebarOpen = true" aria-label="Open menu">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="dms-topbar-title">
        @isset($header)
            {{ $header }}
        @else
            {{ config('app.name', 'DMS') }}
        @endisset
    </div>

    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button type="button" style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.5rem 0.75rem;border:1px solid #e2e8f0;border-radius:0.5rem;background:#fff;font-size:0.875rem;font-weight:500;color:#334155;cursor:pointer;">
                <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                <span style="display:inline-flex;width:2rem;height:2rem;align-items:center;justify-content:center;border-radius:9999px;background:#e0e7ff;color:#4338ca;font-size:0.75rem;font-weight:600;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
            </button>
        </x-slot>
        <x-slot name="content">
            <div class="px-4 py-2 text-xs text-gray-500 border-b">{{ Auth::user()->email }}</div>
            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>
</header>
