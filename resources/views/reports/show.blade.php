<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Report: {{ str_replace('_', ' ', ucfirst($type)) }}</h2>
            @can('reports.export')
                <div class="space-x-2 text-sm">
                    <a href="{{ route('reports.export', array_merge(['type' => $type, 'format' => 'xlsx'], request()->query())) }}" class="px-3 py-1 bg-gray-800 text-white rounded-md">Export Excel</a>
                    <a href="{{ route('reports.export', array_merge(['type' => $type, 'format' => 'csv'], request()->query())) }}" class="px-3 py-1 border rounded-md">Export CSV</a>
                </div>
            @endcan
        </div>
    </x-slot>

    @if ($type !== 'supplier_wise' && $type !== 'client_wise' && $type !== 'renewal_history')
        @include('domains._filters', [
            'resetUrl' => route('reports.show', $type),
            'showPurchaseDates' => true,
        ])
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        @if ($type === 'renewal_history')
            @include('reports.partials.renewals-table', ['renewals' => $renewals])
        @elseif ($type === 'client_wise')
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Client</th><th class="px-4 py-3 text-left">Domains</th></tr></thead>
                <tbody>
                    @foreach ($clientRows as $row)
                        <tr class="border-t"><td class="px-4 py-3">{{ $row->name }}</td><td class="px-4 py-3">{{ $row->domains_count }}</td></tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $clientRows->links() }}</div>
        @elseif ($type === 'supplier_wise')
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Supplier</th><th class="px-4 py-3 text-left">Domains</th></tr></thead>
                <tbody>
                    @foreach ($supplierRows as $row)
                        <tr class="border-t"><td class="px-4 py-3">{{ $row->name }}</td><td class="px-4 py-3">{{ $row->domains_count }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Domain</th>
                        <th class="px-4 py-3 text-left">Client</th>
                        <th class="px-4 py-3 text-left">Expiry</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($domains as $domain)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $domain->domain_name }}</td>
                            <td class="px-4 py-3">{{ $domain->client?->name }}</td>
                            <td class="px-4 py-3">{{ $domain->current_expiry_date->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">{{ $domain->project_status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">No records.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $domains->links() }}</div>
        @endif
    </div>
</x-app-layout>
