<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Expiry Alerts</h2></x-slot>

    <div class="mb-4 flex flex-wrap gap-2 text-sm">
        @foreach (['expired' => 'Expired', '7' => '7 Days', '15' => '15 Days', '30' => '30 Days', '60' => '60 Days'] as $key => $label)
            <a href="{{ route('expiry.index', ['window' => $key]) }}"
               class="px-3 py-1 rounded-md {{ request('window', '30') == $key ? 'bg-gray-800 text-white' : 'bg-white border' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @include('domains._filters', ['resetUrl' => route('expiry.index', ['window' => $window ?? request('window', '30')])])

    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">Domain</th>
                    <th class="px-4 py-3">Client</th>
                    <th class="px-4 py-3">Expiry</th>
                    <th class="px-4 py-3">Days Left</th>
                    <th class="px-4 py-3">Domain By Us</th>
                    <th class="px-4 py-3">Hosting By Us</th>
                    <th class="px-4 py-3">Notified</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($domains as $domain)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $domain->domain_name }}</td>
                        <td class="px-4 py-3">{{ $domain->client?->name }}</td>
                        <td class="px-4 py-3">{{ $domain->current_expiry_date?->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $domain->days_until_expiry ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $domain->domain_managed_by_us == 1 ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">{{ $domain->hosting_managed_by_us == 1 ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">{{ $domain->client_notified ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3 space-x-2">
                            @if ($domain->current_expiry_date)
                                @can('expiry.mark_notified')
                                    <form method="POST" action="{{ route('expiry.mark-notified', $domain) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-indigo-600">Mark Notified</button>
                                    </form>
                                @endcan
                                @can('expiry.notify')
                                    <form method="POST" action="{{ route('expiry.notify', $domain) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="recipient_type" value="client" />
                                        <button type="submit" class="text-indigo-600">Email Client</button>
                                    </form>
                                    <form method="POST" action="{{ route('expiry.notify', $domain) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="recipient_type" value="admin" />
                                        <button type="submit" class="text-indigo-600">Email Admin</button>
                                    </form>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No domains in this alert window.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $domains->links() }}</div>
</x-app-layout>
