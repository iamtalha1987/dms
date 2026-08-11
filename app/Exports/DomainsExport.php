<?php

namespace App\Exports;

use App\Models\Domain;
use App\Models\DomainRenewal;
use App\Support\DomainQueryFilter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DomainsExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected Request $request,
        protected string $type = 'all_domains',
    ) {}

    public function collection()
    {
        if ($this->type === 'renewal_history') {
            return DomainRenewal::query()
                ->with(['domain.client', 'supplier'])
                ->orderByDesc('renewal_date')
                ->get()
                ->map(fn ($r) => [
                    $r->domain?->domain_name,
                    $r->domain?->client?->name,
                    $r->renewal_date?->format('Y-m-d'),
                    $r->new_expiry_date?->format('Y-m-d'),
                    format_money($r->renewal_price),
                    $r->supplier?->name ?? $r->supplier_other,
                    $r->remarks,
                ]);
        }

        $query = Domain::query()->with(['client', 'supplier']);
        $query = match ($this->type) {
            'active' => $query->active(),
            'deactivated' => $query->deactivated(),
            'managed_domain' => $query->managedDomain(),
            'managed_hosting' => $query->managedHosting(),
            'expired' => $query->expired(),
            'upcoming_expiry' => $query->expiringWithin((int) $this->request->input('days', 30)),
            default => $query,
        };

        if ($this->type !== 'upcoming_expiry') {
            $query = DomainQueryFilter::fromRequest($this->request)->apply($query);
        }

        return $query->get()->map(fn ($d) => [
            $d->domain_name,
            $d->client?->name,
            $d->purchase_date?->format('Y-m-d'),
            format_money($d->purchase_price),
            $d->current_expiry_date?->format('Y-m-d'),
            $d->supplier?->name ?? $d->supplier_other,
            $d->domain_managed_by_us ? 'Yes' : 'No',
            $d->hosting_managed_by_us ? 'Yes' : 'No',
            $d->project_status,
            $d->remarks,
        ]);
    }

    public function headings(): array
    {
        if ($this->type === 'renewal_history') {
            return ['Domain', 'Client', 'Renewal Date', 'New Expiry', 'Price', 'Supplier', 'Remarks'];
        }

        return [
            'Domain', 'Client', 'Purchase Date', 'Purchase Price', 'Expiry Date',
            'Supplier', 'Domain By Us', 'Hosting By Us', 'Status', 'Remarks',
        ];
    }
}
