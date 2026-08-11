<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Domain;
use App\Models\DomainRenewal;
use App\Models\Supplier;
use App\Support\DomainQueryFilter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reports.view');
    }

    public function index(): View
    {
        $reports = [
            ['type' => 'all_domains', 'label' => 'All Domains / Projects'],
            ['type' => 'active', 'label' => 'Active Projects'],
            ['type' => 'deactivated', 'label' => 'Deactivated Projects'],
            ['type' => 'managed_domain', 'label' => 'Domains Managed By Us'],
            ['type' => 'managed_hosting', 'label' => 'Hosting Managed By Us'],
            ['type' => 'expired', 'label' => 'Expired Domains'],
            ['type' => 'upcoming_expiry', 'label' => 'Upcoming Expiry Report'],
            ['type' => 'supplier_wise', 'label' => 'Supplier-wise Report'],
            ['type' => 'client_wise', 'label' => 'Client-wise Report'],
            ['type' => 'renewal_history', 'label' => 'Domain Renewal History'],
        ];

        return view('reports.index', compact('reports'));
    }

    public function show(Request $request, string $type): View|RedirectResponse
    {
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $suppliers = Supplier::orderBy('name')->get();

        if ($type === 'renewal_history') {
            $renewals = DomainRenewal::query()
                ->with(['domain.client', 'supplier', 'creator'])
                ->when($request->filled('client_id'), function ($q) use ($request) {
                    $q->whereHas('domain', fn ($d) => $d->where('client_id', $request->integer('client_id')));
                })
                ->orderByDesc('renewal_date')
                ->paginate(25)
                ->withQueryString();

            return view('reports.show', compact('type', 'renewals', 'clients', 'suppliers'));
        }

        if ($type === 'client_wise') {
            $clientRows = Client::withCount('domains')
                ->when($request->filled('client_id'), fn ($q) => $q->where('id', $request->integer('client_id')))
                ->orderBy('name')
                ->paginate(25)
                ->withQueryString();

            return view('reports.show', compact('type', 'clientRows', 'clients', 'suppliers'));
        }

        if ($type === 'supplier_wise') {
            $supplierRows = Supplier::withCount('domains')
                ->orderBy('name')
                ->paginate(25)
                ->withQueryString();

            return view('reports.show', compact('type', 'supplierRows', 'clients', 'suppliers'));
        }

        $filter = DomainQueryFilter::fromRequest($request);
        $query = $this->applyReportType(
            Domain::query()->with(['client', 'supplier']),
            $type,
            $request
        );

        if ($type === 'upcoming_expiry') {
            $query->expiringWithin((int) $request->input('days', 30));
        }

        $result = $filter->paginate(
            $filter->apply($query),
            25,
            'reports.show',
            ['type' => $type]
        );

        if ($result instanceof RedirectResponse) {
            return $result;
        }

        /** @var LengthAwarePaginator $domains */
        $domains = $result;

        return view('reports.show', compact('type', 'domains', 'clients', 'suppliers', 'filter'));
    }

    protected function applyReportType($query, string $type, Request $request)
    {
        return match ($type) {
            'active' => $query->active(),
            'deactivated' => $query->deactivated(),
            'managed_domain' => $query->managedDomain(),
            'managed_hosting' => $query->managedHosting(),
            'expired' => $query->expired(),
            default => $query,
        };
    }
}
