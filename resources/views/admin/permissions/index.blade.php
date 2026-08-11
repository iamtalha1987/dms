<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Permissions by Role</h2></x-slot>
    @can('permissions.manage')
        <form method="POST" action="{{ route('permissions.update') }}" class="bg-white rounded-lg shadow-sm p-4 overflow-x-auto">
            @csrf @method('PUT')
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left sticky left-0 bg-gray-50">Permission</th>
                        @foreach ($roles as $role)
                            <th class="px-3 py-2 text-center whitespace-nowrap">{{ $role->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr class="border-t">
                            <td class="px-3 py-2 sticky left-0 bg-white">{{ $permission->name }}</td>
                            @foreach ($roles as $role)
                                <td class="px-3 py-2 text-center">
                                    @if ($role->name === 'Super Admin')
                                        <span class="text-gray-400">all</span>
                                    @else
                                        <input type="checkbox"
                                            name="roles[{{ $role->id }}][]"
                                            value="{{ $permission->name }}"
                                            @checked($role->hasPermissionTo($permission->name)) />
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4"><x-primary-button>Save Permissions</x-primary-button></div>
        </form>
    @else
        <p class="text-gray-600">You have view-only access to permissions.</p>
    @endcan
</x-app-layout>
