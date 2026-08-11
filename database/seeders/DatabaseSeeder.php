<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SupplierSeeder::class,
            RolesAndPermissionsSeeder::class,
            SettingsSeeder::class,
        ]);

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@dms.local'],
            [
                'name' => 'Super Admin',
                'phone' => null,
                'is_active' => true,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['Super Admin']);
    }
}
