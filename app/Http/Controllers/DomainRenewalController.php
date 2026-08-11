<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\DomainRenewal;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainRenewalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:renewals.view')->only(['index', 'globalIndex']);
        $this->middleware('permission:renewals.create')->only(['create', 'store']);
        $this->middleware('permission:renewals.edit')->only(['edit', 'update']);
        $this->middleware('permission:renewals.delete')->only(['destroy']);
    }

    public function globalIndex(Request $request): View
    {
        $renewals = DomainRenewal::query()
            ->with(['domain.client', 'supplier', 'creator'])
            ->when($request->filled('domain_id'), fn ($q) => $q->where('domain_id', $request->integer('domain_id')))
            ->orderByDesc('renewal_date')
            ->paginate(20)
            ->withQueryString();

        return view('renewals.index', compact('renewals'));
    }

    public function index(Domain $domain): View
    {
        $domain->load(['client', 'renewals.supplier', 'renewals.creator']);

        return view('renewals.domain', compact('domain'));
    }

    public function create(Domain $domain): View
    {
        return view('renewals.create', [
            'domain' => $domain,
            'suppliers' => Supplier::active()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, Domain $domain): RedirectResponse
    {
        $validated = $this->validateRenewal($request);
        $validated['domain_id'] = $domain->id;
        $validated['created_by'] = auth()->id();

        DomainRenewal::create($validated);

        return redirect()->route('domains.show', $domain)->with('success', 'Renewal added successfully.');
    }

    public function edit(DomainRenewal $renewal): View
    {
        $renewal->load('domain.client');

        return view('renewals.edit', [
            'renewal' => $renewal,
            'suppliers' => Supplier::active()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, DomainRenewal $renewal): RedirectResponse
    {
        $renewal->update($this->validateRenewal($request));

        return redirect()->route('domains.show', $renewal->domain_id)->with('success', 'Renewal updated successfully.');
    }

    public function destroy(DomainRenewal $renewal): RedirectResponse
    {
        $domainId = $renewal->domain_id;
        $renewal->delete();

        return redirect()->route('domains.show', $domainId)->with('success', 'Renewal deleted successfully.');
    }

    protected function validateRenewal(Request $request): array
    {
        return $request->validate([
            'renewal_date' => ['required', 'date'],
            'new_expiry_date' => ['required', 'date', 'after_or_equal:renewal_date'],
            'renewal_price' => ['required', 'numeric', 'min:0'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'supplier_other' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
        ]);
    }
}
