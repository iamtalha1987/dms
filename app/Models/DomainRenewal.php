<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DomainRenewal extends Model
{
    protected $fillable = [
        'domain_id',
        'renewal_date',
        'new_expiry_date',
        'renewal_price',
        'supplier_id',
        'supplier_other',
        'remarks',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'renewal_date' => 'date',
            'new_expiry_date' => 'date',
            'renewal_price' => 'decimal:2',
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
