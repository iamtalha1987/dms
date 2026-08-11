<x-app-layout>
    <x-slot name="header">Clients</x-slot>

    <x-page-actions>
        @can('clients.create')
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Add Client</a>
        @endcan
    </x-page-actions>

    <form method="GET" action="{{ route('clients.index') }}" class="mb-4 flex flex-wrap gap-2 rounded-xl bg-white p-4 border border-slate-200 shadow-sm">
        <x-text-input name="search" placeholder="Search..." :value="request('search')" class="w-48" />
        <select name="is_active" class="rounded-md border-gray-300">
            <option value="">All statuses</option>
            <option value="1" @selected(request('is_active') === '1')>Active</option>
            <option value="0" @selected(request('is_active') === '0')>Inactive</option>
        </select>
        <x-primary-button type="submit">Filter</x-primary-button>
        <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50">Reset Filters</a>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Company</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clients as $client)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $client->name }}</td>
                        <td class="px-4 py-3">{{ $client->email }}</td>
                        <td class="px-4 py-3">{{ $client->phone }}</td>
                        <td class="px-4 py-3">{{ $client->company }}</td>
                        <td class="px-4 py-3">{{ $client->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 space-x-2">
                            <a href="{{ route('clients.show', $client) }}" class="text-indigo-600">View</a>
                            @can('clients.edit')<a href="{{ route('clients.edit', $client) }}" class="text-indigo-600">Edit</a>@endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No clients found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $clients->links() }}</div>
</x-app-layout>
