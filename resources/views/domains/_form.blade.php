@php $domain = $domain ?? null; @endphp
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="client_id" value="Client" />
        <select id="client_id" name="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $domain?->client_id) == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <x-input-label for="domain_name" value="Domain Name" />
        <x-text-input id="domain_name" name="domain_name" class="block mt-1 w-full" :value="old('domain_name', $domain?->domain_name)" required />
    </div>
    <div>
        <x-input-label for="purchase_date" value="Purchase Date" />
        <x-text-input id="purchase_date" name="purchase_date" type="date" class="block mt-1 w-full" :value="old('purchase_date', $domain?->purchase_date?->format('Y-m-d'))"
            required />
    </div>
    <div>
        <x-input-label for="purchase_price" value="Purchase Price ({{ currency_symbol() }})" />
        <x-text-input id="purchase_price" name="purchase_price" type="number" step="0.01" class="block mt-1 w-full"
            :value="old('purchase_price', $domain?->purchase_price)" required />
    </div>
    <div>
        <x-input-label for="current_expiry_date" value="Current Expiry Date" />
        <x-text-input id="current_expiry_date" name="current_expiry_date" type="date" class="block mt-1 w-full"
            :value="old('current_expiry_date', $domain?->current_expiry_date?->format('Y-m-d'))" required />
    </div>
    <div>
        <x-input-label for="supplier_id" value="Supplier" />
        <select id="supplier_id" name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">— Select —</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $domain?->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <x-input-label for="supplier_other" value="Other Supplier (if applicable)" />
        <x-text-input id="supplier_other" name="supplier_other" class="block mt-1 w-full" :value="old('supplier_other', $domain?->supplier_other)" />
    </div>
    <div>
        <x-input-label for="project_status" value="Project Status" />
        <select id="project_status" name="project_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            required>
            <option value="active" @selected(old('project_status', $domain?->project_status) === 'active')>Active</option>
            <option value="inactive" @selected(old('project_status', $domain?->project_status) === 'inactive')>Inactive</option>
            <option value="deactivated" @selected(old('project_status', $domain?->project_status) === 'deactivated')>Deactivated</option>
        </select>
    </div>
    <div class="flex items-center gap-6 md:col-span-2">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="domain_managed_by_us" value="1" @checked(old('domain_managed_by_us', $domain?->domain_managed_by_us)) />
            <span>Domain managed by us</span>
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="hosting_managed_by_us" value="1" @checked(old('hosting_managed_by_us', $domain?->hosting_managed_by_us)) />
            <span>Hosting managed by us</span>
        </label>
    </div>
    <div class="md:col-span-2">
        <x-input-label for="remarks" value="Remarks" />
        <textarea id="remarks" name="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('remarks', $domain?->remarks) }}</textarea>
    </div>
</div>
