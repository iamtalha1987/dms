@php $renewal = $renewal ?? null; @endphp
<div>
    <x-input-label for="renewal_date" value="Renewal Date" />
    <x-text-input id="renewal_date" name="renewal_date" type="date" class="block mt-1 w-full" :value="old('renewal_date', $renewal?->renewal_date?->format('Y-m-d'))" required />
</div>
<div>
    <x-input-label for="new_expiry_date" value="New Expiry Date" />
    <x-text-input id="new_expiry_date" name="new_expiry_date" type="date" class="block mt-1 w-full" :value="old('new_expiry_date', $renewal?->new_expiry_date?->format('Y-m-d'))" required />
</div>
<div>
    <x-input-label for="renewal_price" value="Renewal Price ({{ currency_symbol() }})" />
    <x-text-input id="renewal_price" name="renewal_price" type="number" step="0.01" class="block mt-1 w-full" :value="old('renewal_price', $renewal?->renewal_price)" required />
</div>
<div>
    <x-input-label for="supplier_id" value="Supplier" />
    <select name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        <option value="">— Select —</option>
        @foreach ($suppliers as $supplier)
            <option value="{{ $supplier->id }}" @selected(old('supplier_id', $renewal?->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
        @endforeach
    </select>
</div>
<div>
    <x-input-label for="remarks" value="Remarks" />
    <textarea name="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('remarks', $renewal?->remarks) }}</textarea>
</div>
