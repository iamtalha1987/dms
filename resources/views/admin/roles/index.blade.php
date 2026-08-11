<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Roles</h2>
            @can('roles.manage')
                <a href="{{ route('roles.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md">Add Role</a>
            @endcan
        </div>
    </x-slot>
    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Role</th><th class="px-4 py-3 text-left">Permissions</th><th class="px-4 py-3 text-left">Actions</th></tr></thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $role->name }}</td>
                        <td class="px-4 py-3">{{ $role->permissions_count }}</td>
                        <td class="px-4 py-3">
                            @can('roles.manage')
                                @if ($role->name !== 'Super Admin')
                                    <a href="{{ route('roles.edit', $role) }}" class="text-indigo-600">Edit</a>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
