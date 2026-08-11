@props([
    'resetUrl' => route('domains.index'),
    'showPurchaseDates' => false,
])

<form method="GET" action="{{ request()->url() }}"
    class="mb-4 bg-white p-4 rounded-lg shadow-sm border border-slate-200 grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
    @if (request()->routeIs('expiry.*'))
        <input type="hidden" name="window" value="{{ request('window', '30') }}" />
    @endif

    <div class="md:col-span-2">
        <label class="block text-xs font-medium text-slate-500 mb-1">Search domain or client</label>
        <x-text-input name="search" placeholder="e.g. example.com or client name" :value="request('search')" class="w-full" />
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Client</label>
        <select name="client_id" class="w-full rounded-md border-gray-300 shadow-sm">
            <option value="">All clients</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected((string) request('client_id') === (string) $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Supplier</label>
        <select name="supplier_id" class="w-full rounded-md border-gray-300 shadow-sm">
            <option value="">All suppliers</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected((string) request('supplier_id') === (string) $supplier->id)>{{ $supplier->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Project status</label>
        <select name="project_status" class="w-full rounded-md border-gray-300 shadow-sm">
            <option value="">All statuses</option>
            <option value="active" @selected(request('project_status') === 'active')>Active</option>
            <option value="inactive" @selected(request('project_status') === 'inactive')>Inactive</option>
            <option value="deactivated" @selected(request('project_status') === 'deactivated')>Deactivated</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Domain managed by us</label>
        <select name="domain_managed_by_us" class="w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Any</option>
            <option value="1" @selected(request('domain_managed_by_us') === '1')>Yes</option>
            <option value="0" @selected(request('domain_managed_by_us') === '0')>No</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Hosting managed by us</label>
        <select name="hosting_managed_by_us" class="w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Any</option>
            <option value="1" @selected(request('hosting_managed_by_us') === '1')>Yes</option>
            <option value="0" @selected(request('hosting_managed_by_us') === '0')>No</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Expiry from</label>
        <x-text-input type="date" name="expiry_from" :value="request('expiry_from')" class="w-full" />
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Expiry to</label>
        <x-text-input type="date" name="expiry_to" :value="request('expiry_to')" class="w-full" />
    </div>

    @if ($showPurchaseDates)
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Purchase from</label>
            <x-text-input type="date" name="purchase_from" :value="request('purchase_from')" class="w-full" />
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Purchase to</label>
            <x-text-input type="date" name="purchase_to" :value="request('purchase_to')" class="w-full" />
        </div>
    @endif

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Sort by</label>
        <select name="sort" class="w-full rounded-md border-gray-300 shadow-sm">
            <option value="current_expiry_date" @selected(request('sort', 'current_expiry_date') === 'current_expiry_date')>Expiry date</option>
            <option value="purchase_date" @selected(request('sort') === 'purchase_date')>Purchase date</option>
            <option value="domain_name" @selected(request('sort') === 'domain_name')>Domain name</option>
            <option value="client_name" @selected(request('sort') === 'client_name')>Client name</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Direction</label>
        <select name="direction" class="w-full rounded-md border-gray-300 shadow-sm">
            <option value="asc" @selected(request('direction', 'asc') === 'asc')>Ascending</option>
            <option value="desc" @selected(request('direction') === 'desc')>Descending</option>
        </select>
    </div>

    <div class="md:col-span-4 flex flex-wrap items-center gap-2 pt-1">
        <x-primary-button type="submit">Apply Filters</x-primary-button>
        <a href="{{ $resetUrl }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50 shadow-sm">
            Reset Filters
        </a>
        @if (isset($filter) && $filter->hasActiveFilters())
            <span class="text-xs text-slate-500">Filters are active</span>
        @elseif (request()->hasAny([
                'search',
                'client_id',
                'supplier_id',
                'project_status',
                'domain_managed_by_us',
                'hosting_managed_by_us',
                'expiry_from',
                'expiry_to',
                'purchase_from',
                'purchase_to',
            ]))
            <span class="text-xs text-slate-500">Filters are active</span>
        @endif
    </div>
</form>
