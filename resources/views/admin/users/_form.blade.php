@php $user = $user ?? null; @endphp
<div>
    <x-input-label for="name" value="Name" />
    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $user?->name)" required />
</div>
<div>
    <x-input-label for="email" value="Email" />
    <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email', $user?->email)" required />
</div>
<div>
    <x-input-label for="phone" value="Phone" />
    <x-text-input id="phone" name="phone" class="block mt-1 w-full" :value="old('phone', $user?->phone)" />
</div>
<div>
    <x-input-label for="role" value="Role" />
    <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @foreach ($roles as $roleName => $label)
            <option value="{{ $roleName }}" @selected(old('role', $user?->roles->first()?->name) === $roleName)>{{ $roleName }}</option>
        @endforeach
    </select>
</div>
<div>
    <x-input-label for="password" value="{{ $user ? 'New Password (optional)' : 'Password' }}" />
    <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" />
</div>
<div>
    <x-input-label for="password_confirmation" value="Confirm Password" />
    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full" />
</div>
<label class="inline-flex items-center gap-2">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user?->is_active ?? true)) />
    <span>Active</span>
</label>
