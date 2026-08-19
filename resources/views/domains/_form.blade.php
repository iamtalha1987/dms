@php
    $domain = $domain ?? null;
    $domainManagedInitial = old('domain_managed_by_us', $domain?->domain_managed_by_us) ? 'true' : 'false';
    $hostingManagedInitial = old('hosting_managed_by_us', $domain?->hosting_managed_by_us) ? 'true' : 'false';
    $hostingLifetimeInitial = old('hosting_lifetime', $domain?->hosting_lifetime) ? 'true' : 'false';
@endphp
<div
    x-data="{
        domainManaged: {{ $domainManagedInitial }},
        hostingManaged: {{ $hostingManagedInitial }},
        hostingLifetime: {{ $hostingLifetimeInitial }},
    }"
    class="grid grid-cols-1 md:grid-cols-2 gap-4"
>
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

    <div class="flex items-center gap-6 md:col-span-2 border border-slate-200 rounded-md p-3 bg-slate-50">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="domain_managed_by_us" value="1" x-model="domainManaged" />
            <span>Domain managed by us</span>
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="hosting_managed_by_us" value="1" x-model="hostingManaged" />
            <span>Hosting managed by us</span>
        </label>
    </div>

    <div x-show="domainManaged" x-cloak>
        <x-input-label for="purchase_date" value="Purchase Date" />
        <x-text-input id="purchase_date" name="purchase_date" type="date" class="block mt-1 w-full"
            :value="old('purchase_date', $domain?->purchase_date?->format('Y-m-d'))" x-bind:required="domainManaged" />
    </div>
    <div x-show="domainManaged" x-cloak>
        <x-input-label for="current_expiry_date" value="Current Expiry Date (Renewal Date)" />
        <x-text-input id="current_expiry_date" name="current_expiry_date" type="date" class="block mt-1 w-full"
            :value="old('current_expiry_date', $domain?->current_expiry_date?->format('Y-m-d'))" x-bind:required="domainManaged" />
    </div>
    <p x-show="!domainManaged" x-cloak class="md:col-span-2 text-xs text-slate-500 -mt-2">
        Purchase date and renewal date aren't required because this domain isn't managed by us.
    </p>

    <div>
        <x-input-label for="purchase_price" value="Purchase Price ({{ currency_symbol() }})" />
        <x-text-input id="purchase_price" name="purchase_price" type="number" step="0.01" class="block mt-1 w-full"
            :value="old('purchase_price', $domain?->purchase_price)" required />
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

    <div x-show="hostingManaged" x-cloak>
        <x-input-label for="hosting_creation_date" value="Hosting Creation Date" />
        <x-text-input id="hosting_creation_date" name="hosting_creation_date" type="date" class="block mt-1 w-full"
            :value="old('hosting_creation_date', $domain?->hosting_creation_date?->format('Y-m-d'))" x-bind:required="hostingManaged" />
    </div>
    <div x-show="hostingManaged" x-cloak class="flex items-center">
        <label class="inline-flex items-center gap-2 mt-6">
            <input type="checkbox" name="hosting_lifetime" value="1" x-model="hostingLifetime" />
            <span>Lifetime Hosting</span>
        </label>
    </div>
    <div x-show="hostingManaged && !hostingLifetime" x-cloak>
        <x-input-label for="hosting_expiry_date" value="Hosting Expiry Date" />
        <x-text-input id="hosting_expiry_date" name="hosting_expiry_date" type="date" class="block mt-1 w-full"
            :value="old('hosting_expiry_date', $domain?->hosting_expiry_date?->format('Y-m-d'))"
            x-bind:required="hostingManaged && !hostingLifetime" />
    </div>
    <p x-show="hostingManaged && hostingLifetime" x-cloak class="text-xs text-slate-500 self-center">
        No expiry date needed — hosting is lifetime.
    </p>

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

    <div class="md:col-span-2">
        <x-input-label for="remarks" value="Remarks" />
        <textarea id="remarks" name="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('remarks', $domain?->remarks) }}</textarea>
    </div>
</div>
