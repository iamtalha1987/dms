<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Domain;
use App\Models\DomainRenewal;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $cards = [];

        if (auth()->user()->can('clients.view')) {
            $cards[] = ['label' => 'Total Clients', 'value' => Client::count(), 'color' => 'indigo'];
        }

        if (auth()->user()->can('domains.view')) {
            $cards[] = ['label' => 'Total Domains', 'value' => Domain::count(), 'color' => 'blue'];
            $cards[] = ['label' => 'Active Projects', 'value' => Domain::active()->count(), 'color' => 'green'];
            $cards[] = ['label' => 'Deactivated Projects', 'value' => Domain::deactivated()->count(), 'color' => 'gray'];
            $cards[] = ['label' => 'Domains Managed By Us', 'value' => Domain::managedDomain()->count(), 'color' => 'teal'];
            $cards[] = ['label' => 'Hosting Managed By Us', 'value' => Domain::managedHosting()->count(), 'color' => 'cyan'];
            $cards[] = ['label' => 'Total Purchase Amount', 'value' => format_money(Domain::sum('purchase_price')), 'color' => 'purple'];
        }

        if (auth()->user()->can('renewals.view')) {
            $cards[] = ['label' => 'Total Renewal Amount', 'value' => format_money(DomainRenewal::sum('renewal_price')), 'color' => 'violet'];
        }

        if (auth()->user()->can('expiry.view')) {
            $cards[] = ['label' => 'Expired Domains', 'value' => Domain::expired()->count(), 'color' => 'red'];
            $cards[] = ['label' => 'Expiring in 30 Days', 'value' => Domain::expiringWithin(30)->count(), 'color' => 'orange'];
        }

        $supplierStats = collect();
        if (auth()->user()->can('domains.view')) {
            $supplierStats = Domain::query()
                ->select('supplier_id', DB::raw('count(*) as total'))
                ->whereNotNull('supplier_id')
                ->groupBy('supplier_id')
                ->with('supplier')
                ->get()
                ->map(fn ($row) => [
                    'name' => $row->supplier?->name ?? 'Unknown',
                    'total' => $row->total,
                ]);
        }

        return view('dashboard', compact('cards', 'supplierStats'));
    }
}
