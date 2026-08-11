<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Renewal History</h2></x-slot>
    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">Domain</th>
                    <th class="px-4 py-3">Client</th>
                    <th class="px-4 py-3">Renewal Date</th>
                    <th class="px-4 py-3">New Expiry</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($renewals as $renewal)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $renewal->domain?->domain_name }}</td>
                        <td class="px-4 py-3">{{ $renewal->domain?->client?->name }}</td>
                        <td class="px-4 py-3">{{ $renewal->renewal_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ $renewal->new_expiry_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ format_money($renewal->renewal_price) }}</td>
                        <td class="px-4 py-3">
                            @can('renewals.edit')<a href="{{ route('renewals.edit', $renewal) }}" class="text-indigo-600">Edit</a>@endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No renewals.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $renewals->links() }}</div>
</x-app-layout>
