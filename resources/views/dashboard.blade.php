<x-app-layout>
    <x-slot name="header">{{ __('Dashboard') }}</x-slot>

    <div style="margin-bottom:1.5rem;border-radius:0.75rem;background:linear-gradient(to right,#4f46e5,#3730a3);padding:1.5rem;color:#fff;box-shadow:0 4px 6px -1px rgb(0 0 0 / 0.1);" class="mb-6 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-800 p-6 text-white shadow-lg">
        <h1 style="margin:0;font-size:1.5rem;font-weight:700;">Welcome back, {{ Auth::user()->name }}</h1>
        <p style="margin:0.25rem 0 0;font-size:0.875rem;opacity:0.9;">Talha  Manage domains, clients, renewals, and expiry alerts from one place.</p>
    </div>

    @if (count($cards))
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;margin-bottom:2rem;" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">
            @foreach ($cards as $card)
                <x-stat-card :label="$card['label']" :value="$card['value']" />
            @endforeach
        </div>
    @else
        <div style="background:#fff;padding:1.5rem;border-radius:0.75rem;border:1px solid #e2e8f0;color:#475569;">No dashboard metrics available for your role.</div>
    @endif

    <div style="display:grid;grid-template-columns:1fr;gap:1.5rem;" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @if ($supplierStats->isNotEmpty())
            <div style="background:#fff;border-radius:0.75rem;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 2px rgb(0 0 0 / 0.05);" class="lg:col-span-2 rounded-xl bg-white shadow-sm border overflow-hidden">
                <div style="padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;">
                    <h3 style="margin:0;font-size:1.125rem;font-weight:600;color:#1e293b;">Supplier-wise Domain Count</h3>
                </div>
                <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
                    <thead style="background:#f8fafc;color:#64748b;">
                        <tr>
                            <th style="padding:0.75rem 1.5rem;text-align:left;font-weight:500;">Supplier</th>
                            <th style="padding:0.75rem 1.5rem;text-align:left;font-weight:500;">Domains</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supplierStats as $row)
                            <tr style="border-top:1px solid #f1f5f9;">
                                <td style="padding:0.75rem 1.5rem;">{{ $row['name'] }}</td>
                                <td style="padding:0.75rem 1.5rem;font-weight:600;">{{ $row['total'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div style="background:#fff;border-radius:0.75rem;border:1px solid #e2e8f0;padding:1.5rem;box-shadow:0 1px 2px rgb(0 0 0 / 0.05);">
            <h3 style="margin:0 0 1rem;font-size:1.125rem;font-weight:600;color:#1e293b;">Quick Links</h3>
            <ul style="margin:0;padding:0;list-style:none;font-size:0.875rem;">
                @can('clients.view')
                    <li style="margin-bottom:0.5rem;"><a href="{{ route('clients.index') }}" style="color:#4f46e5;text-decoration:none;">Clients</a></li>
                @endcan
                @can('domains.view')
                    <li style="margin-bottom:0.5rem;"><a href="{{ route('domains.index') }}" style="color:#4f46e5;text-decoration:none;">Domains / Projects</a></li>
                @endcan
                @can('expiry.view')
                    <li style="margin-bottom:0.5rem;"><a href="{{ route('expiry.index') }}" style="color:#4f46e5;text-decoration:none;">Expiry Alerts</a></li>
                @endcan
                @can('reports.view')
                    <li><a href="{{ route('reports.index') }}" style="color:#4f46e5;text-decoration:none;">Reports</a></li>
                @endcan
            </ul>
        </div>
    </div>
</x-app-layout>
