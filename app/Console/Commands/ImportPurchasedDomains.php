<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Domain;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportPurchasedDomains extends Command
{
    protected $signature = 'domains:import {file : Path to the Excel file} {--dry-run : Preview without saving}';

    protected $description = 'Import clients and domains from Purchased Domains spreadsheet';

    public function handle(): int
    {
        $path = $this->argument('file');

        if (! is_readable($path)) {
            $this->error("File not found or not readable: {$path}");

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $sheet = IOFactory::load($path)->getActiveSheet();
        $rows = $sheet->toArray();

        $createdClients = 0;
        $createdDomains = 0;
        $skipped = 0;
        $errors = [];

        $dynadotId = Supplier::query()->where('slug', 'dynadot')->value('id');

        if (! $dynadotId && ! $dryRun) {
            $this->error('Dynadot supplier not found. Run: php artisan db:seed --class=SupplierSeeder');

            return self::FAILURE;
        }

        $defaultPrice = 10.88;

        $import = function () use ($rows, $dryRun, $dynadotId, $defaultPrice, &$createdClients, &$createdDomains, &$skipped, &$errors) {
            $clientCache = [];

            foreach ($rows as $index => $row) {
                if ($index === 0) {
                    continue;
                }

                $clientName = trim((string) ($row[0] ?? ''));
                $domainName = strtolower(trim((string) ($row[1] ?? '')));
                $renewalStatus = trim((string) ($row[2] ?? ''));
                $purchaseRaw = $row[3] ?? null;
                $expiryRaw = $row[4] ?? null;

                if ($domainName === '') {
                    continue;
                }

                if ($clientName === '') {
                    $clientName = 'Unknown Client';
                }

                $purchaseDate = $this->parseDate($purchaseRaw);
                $expiryDate = $this->parseDate($expiryRaw);

                if (! $purchaseDate || ! $expiryDate) {
                    $errors[] = "Row ".($index + 1)." ({$domainName}): invalid dates — purchase: ".json_encode($purchaseRaw).', expiry: '.json_encode($expiryRaw);
                    $skipped++;

                    continue;
                }

                if ($dryRun) {
                    $createdDomains++;

                    continue;
                }

                if (! isset($clientCache[$clientName])) {
                    $client = Client::query()->firstOrCreate(
                        ['name' => $clientName],
                        ['is_active' => true]
                    );

                    if ($client->wasRecentlyCreated) {
                        $createdClients++;
                    }

                    $clientCache[$clientName] = $client->id;
                }

                $clientId = $clientCache[$clientName];

                $remarks = $renewalStatus !== '' ? "Renewal status: {$renewalStatus}" : null;

                $domain = Domain::query()->updateOrCreate(
                    ['domain_name' => $domainName],
                    [
                        'client_id' => $clientId,
                        'purchase_date' => $purchaseDate,
                        'purchase_price' => $defaultPrice,
                        'current_expiry_date' => $expiryDate,
                        'supplier_id' => $dynadotId,
                        'project_status' => 'active',
                        'domain_managed_by_us' => true,
                        'hosting_managed_by_us' => false,
                        'remarks' => $remarks,
                        'created_by' => null,
                    ]
                );

                if ($domain->wasRecentlyCreated) {
                    $createdDomains++;
                }
            }
        };

        if ($dryRun) {
            $import();
            $this->info("Dry run: would import {$createdDomains} domains (skipped {$skipped}).");

            foreach (array_slice($errors, 0, 10) as $error) {
                $this->warn($error);
            }

            return self::SUCCESS;
        }

        DB::transaction($import);

        $this->info("Import complete.");
        $this->line("Clients created: {$createdClients}");
        $this->line("Domains created: {$createdDomains}");
        $this->line("Skipped: {$skipped}");

        if (count($errors)) {
            $this->warn('Issues:');
            foreach ($errors as $error) {
                $this->warn("  - {$error}");
            }
        }

        return self::SUCCESS;
    }

    protected function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_numeric($value)) {
            try {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value))->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }

        $string = trim((string) $value);

        // "2025/05/20 10:22 PST"
        if (preg_match('/^(\d{4})\/(\d{1,2})\/(\d{1,2})/', $string, $m)) {
            return sprintf('%04d-%02d-%02d', (int) $m[1], (int) $m[2], (int) $m[3]);
        }

        // "20-May-2026"
        try {
            return Carbon::parse($string)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
