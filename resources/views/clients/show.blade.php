<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $client->name }}</h2>
            @can('clients.edit')
                <a href="{{ route('clients.edit', $client) }}" class="text-sm text-indigo-600">Edit</a>
            @endcan
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <p><strong>Email:</strong> {{ $client->email ?? '—' }}</p>
        <p><strong>Phone:</strong> {{ $client->phone ?? '—' }}</p>
        <p><strong>Company:</strong> {{ $client->company ?? '—' }}</p>
    </div>

    <h3 class="font-semibold mb-3">Domains</h3>
    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Domain</th><th class="px-4 py-3 text-left">Expiry</th><th class="px-4 py-3 text-left">Status</th></tr></thead>
            <tbody>
                @forelse ($client->domains as $domain)
                    <tr class="border-t">
                        <td class="px-4 py-3"><a href="{{ route('domains.show', $domain) }}" class="text-indigo-600">{{ $domain->domain_name }}</a></td>
                        <td class="px-4 py-3">{{ $domain->current_expiry_date?->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $domain->project_status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-4 text-gray-500">No domains.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
