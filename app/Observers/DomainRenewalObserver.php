<?php

namespace App\Observers;

use App\Models\DomainRenewal;
use App\Services\DomainExpiryService;

class DomainRenewalObserver
{
    public function __construct(protected DomainExpiryService $expiryService) {}

    public function saved(DomainRenewal $renewal): void
    {
        $this->expiryService->syncCurrentExpiry($renewal->domain);
    }

    public function deleted(DomainRenewal $renewal): void
    {
        $this->expiryService->syncCurrentExpiry($renewal->domain);
    }
}
