@php $client = $client ?? null; @endphp
<div>
    <x-input-label for="name" value="Name" />
    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $client?->name)" required />
</div>
<div>
    <x-input-label for="email" value="Email" />
    <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email', $client?->email)" />
</div>
<div>
    <x-input-label for="phone" value="Phone" />
    <x-text-input id="phone" name="phone" class="block mt-1 w-full" :value="old('phone', $client?->phone)" />
</div>
<div>
    <x-input-label for="company" value="Company" />
    <x-text-input id="company" name="company" class="block mt-1 w-full" :value="old('company', $client?->company)" />
</div>
<label class="inline-flex items-center gap-2">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $client?->is_active ?? true)) />
    <span>Active</span>
</label>
