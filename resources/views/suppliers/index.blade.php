<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Suppliers</h2>
            @can('suppliers.create')
                <a href="{{ route('suppliers.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md">Add Supplier</a>
            @endcan
        </div>
    </x-slot>
    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Name</th><th class="px-4 py-3 text-left">Slug</th><th class="px-4 py-3 text-left">Status</th><th class="px-4 py-3 text-left">Actions</th></tr></thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $supplier->name }}</td>
                        <td class="px-4 py-3">{{ $supplier->slug }}</td>
                        <td class="px-4 py-3">{{ $supplier->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3">
                            @can('suppliers.edit')<a href="{{ route('suppliers.edit', $supplier) }}" class="text-indigo-600">Edit</a>@endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $suppliers->links() }}</div>
</x-app-layout>
