<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['name' => 'Namecheap', 'slug' => 'namecheap'],
            ['name' => 'Dynadot', 'slug' => 'dynadot'],
            ['name' => 'Other', 'slug' => 'other'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::query()->updateOrCreate(
                ['slug' => $supplier['slug']],
                ['name' => $supplier['name'], 'is_active' => true]
            );
        }
    }
}
