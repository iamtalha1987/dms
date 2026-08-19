<x-app-layout>
    <x-slot name="header">Domains / Projects</x-slot>

    <x-page-actions>
        @can('domains.create')
            <a href="{{ route('domains.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Add Domain</a>
        @endcan
    </x-page-actions>

    @include('domains._filters', ['resetUrl' => route('domains.index'), 'showPurchaseDates' => true])

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">Domain</th>
                    <th class="px-4 py-3">Client</th>
                    <th class="px-4 py-3">Expiry</th>
                    <th class="px-4 py-3">Supplier</th>
                    <th class="px-4 py-3">Domain By Us</th>
                    <th class="px-4 py-3">Hosting By Us</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($domains as $domain)
                    <tr class="border-t {{ $domain->is_expired ? 'bg-red-50' : '' }}">
                        <td class="px-4 py-3">{{ $domain->domain_name }}</td>
                        <td class="px-4 py-3">{{ $domain->client?->name }}</td>
                        <td class="px-4 py-3">{{ $domain->current_expiry_date?->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $domain->supplier?->name ?? $domain->supplier_other ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $domain->domain_managed_by_us == 1 ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">{{ $domain->hosting_managed_by_us == 1 ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">{{ $domain->project_status }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('domains.show', $domain) }}" class="text-indigo-600">View</a>
                            @can('domains.edit')<a href="{{ route('domains.edit', $domain) }}" class="text-indigo-600 ms-2">Edit</a>@endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No domains found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $domains->links() }}</div>
</x-app-layout>
