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

class ExpiryAlertController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:expiry.view')->only(['index']);
        $this->middleware('permission:expiry.mark_notified')->only(['markNotified']);
    }

    public function index(Request $request): View|RedirectResponse
    {
        $window = $request->string('window', '30')->toString();
        $request->merge(['window' => $window]);

        $filter = DomainQueryFilter::fromRequest($request);
        $query = $filter->baseQuery();

        if ($window !== 'expired') {
            $query->active();
        }

        $result = $filter->paginate(
            $filter->apply($query),
            20,
            'expiry.index',
            ['window' => $window]
        );

        if ($result instanceof RedirectResponse) {
            return $result;
        }

        /** @var LengthAwarePaginator $domains */
        $domains = $result;
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('expiry.index', compact('domains', 'clients', 'suppliers', 'filter', 'window'));
    }

    public function markNotified(Domain $domain): RedirectResponse
    {
        $domain->update([
            'client_notified' => true,
            'client_notified_at' => now(),
        ]);

        return back()->with('success', 'Client marked as notified.');
    }
}
