<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Domain;
use App\Models\Supplier;
use App\Support\DomainQueryFilter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class DomainController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:domains.view')->only(['index', 'show']);
        $this->middleware('permission:domains.create')->only(['create', 'store']);
        $this->middleware('permission:domains.edit')->only(['edit', 'update']);
        $this->middleware('permission:domains.delete')->only(['destroy']);
    }

    public function index(Request $request): View|RedirectResponse
    {
        $filter = DomainQueryFilter::fromRequest($request);
        $result = $filter->paginate(
            $filter->apply($filter->baseQuery()),
            15,
            'domains.index'
        );

        if ($result instanceof RedirectResponse) {
            return $result;
        }

        /** @var LengthAwarePaginator $domains */
        $domains = $result;
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('domains.index', compact('domains', 'clients', 'suppliers', 'filter'));
    }

    public function create(): View
    {
        return view('domains.create', [
            'clients' => Client::active()->orderBy('name')->get(),
            'suppliers' => Supplier::active()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDomain($request);
        $validated['created_by'] = auth()->id();

        Domain::create($validated);

        return redirect()->route('domains.index')->with('success', 'Domain created successfully.');
    }

    public function show(Domain $domain): View
    {
        $domain->load(['client', 'supplier', 'creator', 'renewals.supplier', 'renewals.creator']);

        return view('domains.show', compact('domain'));
    }

    public function edit(Domain $domain): View
    {
        return view('domains.edit', [
            'domain' => $domain,
            'clients' => Client::orderBy('name')->get(),
            'suppliers' => Supplier::active()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Domain $domain): RedirectResponse
    {
        $validated = $this->validateDomain($request, $domain->id);

        $domain->update($validated);

        return redirect()->route('domains.show', $domain)->with('success', 'Domain updated successfully.');
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        $domain->delete();

        return redirect()->route('domains.index')->with('success', 'Domain deleted successfully.');
    }

    protected function validateDomain(Request $request, ?int $domainId = null): array
    {
        // A domain that isn't managed by us doesn't need a purchase/renewal
        // date. If hosting is managed by us instead, we collect the hosting
        // creation date (and expiry date, unless hosting is lifetime) in
        // place of those fields.
        $domainManaged = $request->boolean('domain_managed_by_us');
        $hostingManaged = $request->boolean('hosting_managed_by_us');
        $hostingLifetime = $hostingManaged && $request->boolean('hosting_lifetime');

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'domain_name' => ['required', 'string', 'max:255', 'unique:domains,domain_name,' . $domainId],
            'purchase_date' => [$domainManaged ? 'required' : 'nullable', 'date'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'current_expiry_date' => [$domainManaged ? 'required' : 'nullable', 'date'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'supplier_other' => ['nullable', 'string', 'max:255'],
            'project_status' => ['required', 'in:active,inactive,deactivated'],
            'hosting_creation_date' => [$hostingManaged ? 'required' : 'nullable', 'date'],
            'hosting_lifetime' => ['nullable', 'boolean'],
            'hosting_expiry_date' => [$hostingManaged && ! $hostingLifetime ? 'required' : 'nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ]);

        $validated['domain_managed_by_us'] = $domainManaged;
        $validated['hosting_managed_by_us'] = $hostingManaged;
        $validated['hosting_lifetime'] = $hostingLifetime;

        // Keep stored data clean: don't persist dates for things that
        // aren't being managed, and don't persist a hosting expiry date
        // when hosting is lifetime.
        if (! $domainManaged) {
            $validated['purchase_date'] = null;
            $validated['current_expiry_date'] = null;
        }

        if (! $hostingManaged) {
            $validated['hosting_creation_date'] = null;
            $validated['hosting_expiry_date'] = null;
        } elseif ($hostingLifetime) {
            $validated['hosting_expiry_date'] = null;
        }

        return $validated;
    }
}
