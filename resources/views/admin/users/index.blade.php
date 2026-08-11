<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Users</h2>
            @can('users.create')
                <a href="{{ route('users.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md">Add User</a>
            @endcan
        </div>
    </x-slot>
    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td class="px-4 py-3">{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 space-x-2">
                            @can('users.edit')<a href="{{ route('users.edit', $user) }}" class="text-indigo-600">Edit</a>@endcan
                            @can('users.activate')
                                <form method="POST" action="{{ route('users.toggle-active', $user) }}" class="inline">@csrf @method('PATCH')<button class="text-indigo-600">Toggle</button></form>
                            @endcan
                            @can('users.delete')
                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete user?')">@csrf @method('DELETE')<button class="text-red-600">Delete</button></form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</x-app-layout>
