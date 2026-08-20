<aside
    class="dms-sidebar"
    :class="{ 'is-open': sidebarOpen }"
>
    <div style="display:flex;align-items:center;gap:0.75rem;padding:0 1.25rem;height:4rem;border-bottom:1px solid rgba(148,163,184,0.2);flex-shrink:0;">
        <div style="width:2.25rem;height:2.25rem;border-radius:0.5rem;background:#6366f1;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.875rem;"><img src="{{ asset('favicon.png') }}" alt=""></div>
        <div>
            <p style="margin:0;font-weight:600;font-size:0.875rem;line-height:1.25;">{{ config('app.name', 'DMS') }}</p>
            <p style="margin:0;font-size:0.75rem;color:#94a3b8;">Domain Management</p>
        </div>
    </div>

    <nav style="flex:1;padding:1rem 0.75rem;overflow-y:auto;">
        @foreach ($adminMenu ?? [] as $item)
            @php $active = request()->routeIs($item['pattern']); @endphp
            <a href="{{ route($item['route']) }}" class="{{ $active ? 'active' : '' }}">
                <span style="width:0.5rem;height:0.5rem;border-radius:9999px;background:{{ $active ? '#fff' : '#475569' }};"></span>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div style="padding:1rem;border-top:1px solid rgba(148,163,184,0.2);font-size:0.75rem;color:#64748b;flex-shrink:0;">
        {{ Auth::user()->name ?? '' }}
    </div>
</aside>
