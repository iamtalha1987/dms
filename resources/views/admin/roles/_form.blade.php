@php
    $role = $role ?? null;
    $selected = old('permissions', $role ? $role->permissions->pluck('name')->all() : []);
@endphp
<div class="mb-4 max-w-md">
    <x-input-label for="name" value="Role Name" />
    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $role?->name)" required />
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm max-h-96 overflow-y-auto border p-4 rounded-md">
    @foreach ($permissions as $permission)
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="permissions[]" value="{{ $permission }}" @checked(in_array($permission, $selected, true)) />
            <span>{{ $permission }}</span>
        </label>
    @endforeach
</div>
