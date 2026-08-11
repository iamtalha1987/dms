<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Console\Command;

class ApplyImportDefaults extends Command
{
    protected $signature = 'domains:apply-import-defaults
                            {--price=10.88 : Default purchase price}
                            {--supplier=dynadot : Supplier slug}';

    protected $description = 'Set Dynadot supplier and default purchase price on imported domains';

    public function handle(): int
    {
        $supplierId = Supplier::query()->where('slug', $this->option('supplier'))->value('id');

        if (! $supplierId) {
            $this->error('Supplier not found: '.$this->option('supplier'));

            return self::FAILURE;
        }

        $price = (float) $this->option('price');

        $updated = Domain::query()
            ->where(function ($q) {
                $q->where('purchase_price', 0)
                    ->orWhereNull('supplier_id')
                    ->orWhere('remarks', 'like', 'Renewal status:%');
            })
            ->update([
                'supplier_id' => $supplierId,
                'purchase_price' => $price,
            ]);

        Setting::set('currency_symbol', '$');

        $this->info("Updated {$updated} domains (supplier: Dynadot, price: ".format_money($price).').');
        $this->info('Currency symbol set to $');

        return self::SUCCESS;
    }
}
