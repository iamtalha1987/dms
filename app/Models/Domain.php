<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'domain_name',
        'purchase_date',
        'purchase_price',
        'current_expiry_date',
        'supplier_id',
        'supplier_other',
        'domain_managed_by_us',
        'hosting_managed_by_us',
        'hosting_creation_date',
        'hosting_lifetime',
        'hosting_expiry_date',
        'project_status',
        'remarks',
        'created_by',
        'client_notified',
        'client_notified_at',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'current_expiry_date' => 'date',
            'purchase_price' => 'decimal:2',
            'domain_managed_by_us' => 'boolean',
            'hosting_managed_by_us' => 'boolean',
            'hosting_creation_date' => 'date',
            'hosting_lifetime' => 'boolean',
            'hosting_expiry_date' => 'date',
            'client_notified' => 'boolean',
            'client_notified_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(DomainRenewal::class)->orderByDesc('renewal_date');
    }

    public function notificationLogs(): HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function getIsExpiredAttribute(): bool
    {
        return (bool) $this->current_expiry_date?->isPast();
    }

    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (! $this->current_expiry_date) {
            return null;
        }

        return (int) now()->startOfDay()->diffInDays($this->current_expiry_date, false);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('project_status', 'active');
    }

    public function scopeDeactivated(Builder $query): Builder
    {
        return $query->where('project_status', 'deactivated');
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('current_expiry_date', '<', now()->toDateString());
    }

    public function scopeExpiringWithin(Builder $query, int $days): Builder
    {
        return $query
            ->where('project_status', 'active')
            ->whereBetween('current_expiry_date', [
                now()->toDateString(),
                now()->addDays($days)->toDateString(),
            ]);
    }

    public function scopeManagedDomain(Builder $query): Builder
    {
        return $query->where('domain_managed_by_us', true);
    }

    public function scopeManagedHosting(Builder $query): Builder
    {
        return $query->where('hosting_managed_by_us', true);
    }
}
