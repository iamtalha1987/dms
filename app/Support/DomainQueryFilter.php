<?php

namespace App\Support;

use App\Models\Client;
use App\Models\Domain;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DomainQueryFilter
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        if ($search = $this->normalizedSearch()) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('domain_name', 'like', "%{$search}%")
                    ->orWhereHas('client', fn (Builder $c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        if ($this->request->filled('client_id')) {
            $query->where('client_id', $this->request->integer('client_id'));
        }

        if ($this->request->filled('supplier_id')) {
            $query->where('supplier_id', $this->request->integer('supplier_id'));
        }

        if ($this->request->filled('project_status')) {
            $query->where('project_status', $this->request->string('project_status')->toString());
        }

        if ($this->request->filled('domain_managed_by_us')) {
            $query->where('domain_managed_by_us', $this->request->boolean('domain_managed_by_us'));
        }

        if ($this->request->filled('hosting_managed_by_us')) {
            $query->where('hosting_managed_by_us', $this->request->boolean('hosting_managed_by_us'));
        }

        if ($this->request->filled('expiry_from')) {
            $query->whereDate('current_expiry_date', '>=', $this->request->date('expiry_from'));
        }

        if ($this->request->filled('expiry_to')) {
            $query->whereDate('current_expiry_date', '<=', $this->request->date('expiry_to'));
        }

        if ($this->request->filled('purchase_from')) {
            $query->whereDate('purchase_date', '>=', $this->request->date('purchase_from'));
        }

        if ($this->request->filled('purchase_to')) {
            $query->whereDate('purchase_date', '<=', $this->request->date('purchase_to'));
        }

        if ($this->shouldApplyExpiryWindow()) {
            $window = $this->request->string('window')->toString();

            match ($window) {
                'expired' => $query->expired(),
                '7', '15', '30', '60' => $query->expiringWithin((int) $window),
                default => null,
            };
        }

        $this->applySorting($query);

        return $query;
    }

    public function hasActiveFilters(): bool
    {
        return collect([
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
        ])->contains(fn (string $key) => $this->request->filled($key));
    }

    public function paginate(Builder $query, int $perPage, string $redirectRoute, array $routeParams = []): LengthAwarePaginator|RedirectResponse
    {
        $paginator = $query->paginate($perPage)->withQueryString();

        if ($paginator->isEmpty() && $paginator->currentPage() > 1) {
            return redirect()->route($redirectRoute, array_merge(
                $routeParams,
                $this->request->except('page')
            ));
        }

        return $paginator;
    }

    public static function fromRequest(Request $request): self
    {
        return new self($request);
    }

    public function baseQuery(): Builder
    {
        return Domain::query()->with(['client', 'supplier']);
    }

    protected function normalizedSearch(): ?string
    {
        $search = trim($this->request->input('search', ''));

        return $search !== '' ? strtolower($search) : null;
    }

    protected function shouldApplyExpiryWindow(): bool
    {
        if (! $this->request->routeIs('expiry.*')) {
            return false;
        }

        $window = $this->request->string('window')->toString();

        return in_array($window, ['expired', '7', '15', '30', '60'], true);
    }

    protected function applySorting(Builder $query): void
    {
        $sort = $this->request->string('sort', 'current_expiry_date')->toString();
        $direction = $this->request->string('direction', 'asc')->toString() === 'desc' ? 'desc' : 'asc';

        if ($sort === 'client_name') {
            $query->orderBy(
                Client::query()
                    ->select('name')
                    ->whereColumn('clients.id', 'domains.client_id')
                    ->limit(1),
                $direction
            );

            return;
        }

        $allowed = ['purchase_date', 'current_expiry_date', 'domain_name', 'project_status'];

        if (in_array($sort, $allowed, true)) {
            $query->orderBy($sort, $direction);

            return;
        }

        $query->orderBy('current_expiry_date', 'asc');
    }
}
