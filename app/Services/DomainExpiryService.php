<?php

namespace App\Services;

use App\Models\Domain;

class DomainExpiryService
{
    public function syncCurrentExpiry(Domain $domain): void
    {
        $latest = $domain->renewals()
            ->orderByDesc('new_expiry_date')
            ->orderByDesc('renewal_date')
            ->first();

        if (! $latest) {
            return;
        }

        $previousExpiry = $domain->current_expiry_date?->toDateString();
        $newExpiry = $latest->new_expiry_date->toDateString();

        $data = ['current_expiry_date' => $latest->new_expiry_date];

        if ($previousExpiry !== $newExpiry) {
            $data['client_notified'] = false;
            $data['client_notified_at'] = null;
        }

        $domain->update($data);
    }
}
