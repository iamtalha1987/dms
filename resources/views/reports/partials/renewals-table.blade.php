<table class="min-w-full text-sm">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-3 text-left">Domain</th>
            <th class="px-4 py-3 text-left">Client</th>
            <th class="px-4 py-3 text-left">Renewal Date</th>
            <th class="px-4 py-3 text-left">New Expiry</th>
            <th class="px-4 py-3 text-left">Price</th>
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
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No renewals.</td></tr>
        @endforelse
    </tbody>
</table>
<div class="mt-4">{{ $renewals->links() }}</div>
