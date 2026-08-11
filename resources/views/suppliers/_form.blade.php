@php $supplier = $supplier ?? null; @endphp
<div>
    <x-input-label for="name" value="Name" />
    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $supplier?->name)" required />
</div>
<label class="inline-flex items-center gap-2">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $supplier?->is_active ?? true)) />
    <span>Active</span>
</label>
