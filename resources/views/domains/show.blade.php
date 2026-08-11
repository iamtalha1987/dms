<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $domain->domain_name }}</h2>
            <div class="space-x-3 text-sm">
                @can('renewals.create')
                    <a href="{{ route('domains.renewals.create', $domain) }}" class="text-indigo-600">Add Renewal</a>
                @endcan
                @can('domains.edit')
                    <a href="{{ route('domains.edit', $domain) }}" class="text-indigo-600">Edit</a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 grid md:grid-cols-2 gap-2 text-sm">
        <p><strong>Client:</strong> {{ $domain->client?->name }}</p>
        <p><strong>Expiry:</strong> {{ $domain->current_expiry_date->format('Y-m-d') }}</p>
        <p><strong>Purchase:</strong> {{ $domain->purchase_date->format('Y-m-d') }} — {{ format_money($domain->purchase_price) }}</p>
        <p><strong>Supplier:</strong> {{ $domain->supplier?->name ?? $domain->supplier_other ?? '—' }}</p>
        <p><strong>Managed:</strong> Domain {{ $domain->domain_managed_by_us ? 'Yes' : 'No' }}, Hosting {{ $domain->hosting_managed_by_us ? 'Yes' : 'No' }}</p>
        <p><strong>Status:</strong> {{ $domain->project_status }}</p>
        <p class="md:col-span-2"><strong>Remarks:</strong> {{ $domain->remarks ?? '—' }}</p>
    </div>

    <h3 class="font-semibold mb-3">Renewal History</h3>
    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Renewal Date</th>
                    <th class="px-4 py-3 text-left">New Expiry</th>
                    <th class="px-4 py-3 text-left">Price</th>
                    <th class="px-4 py-3 text-left">Supplier</th>
                    <th class="px-4 py-3 text-left">By</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($domain->renewals as $renewal)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $renewal->renewal_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ $renewal->new_expiry_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ format_money($renewal->renewal_price) }}</td>
                        <td class="px-4 py-3">{{ $renewal->supplier?->name ?? $renewal->supplier_other ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $renewal->creator?->name }}</td>
                        <td class="px-4 py-3">
                            @can('renewals.edit')<a href="{{ route('renewals.edit', $renewal) }}" class="text-indigo-600">Edit</a>@endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-4 text-gray-500">No renewals yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
