<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Creates the default staff accounts. The password comes from the
 * SEED_ADMIN_PASSWORD env variable; locally it falls back to "password",
 * in production a random one is generated and printed once so the live
 * admin panel is never protected by a publicly known password.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $plain = $this->password();
        $password = Hash::make($plain);

        $admin = User::updateOrCreate(
            ['email' => 'admin@crochetstore.test'],
            [
                'name'              => 'Store Admin',
                'password'          => $password,
                'phone'             => '9779800000001',
                'is_active'         => true,
                'email_verified_at' => now(),
            ],
        );
        $admin->syncRoles(['admin']);

        $manager = User::updateOrCreate(
            ['email' => 'manager@crochetstore.test'],
            [
                'name'              => 'Store Manager',
                'password'          => $password,
                'phone'             => '9779800000002',
                'is_active'         => true,
                'email_verified_at' => now(),
            ],
        );
        $manager->syncRoles(['manager']);

        $staff = User::updateOrCreate(
            ['email' => 'staff@crochetstore.test'],
            [
                'name'              => 'Store Staff',
                'password'          => $password,
                'phone'             => '9779800000003',
                'is_active'         => true,
                'email_verified_at' => now(),
            ],
        );
        $staff->syncRoles(['staff']);
    }

    private function password(): string
    {
        if ($fromConfig = config('crochet.seed_admin_password')) {
            return $fromConfig;
        }

        if (! app()->isProduction()) {
            return 'password';
        }

        $generated = Str::password(20, symbols: false);
        $this->command?->warn("SEED_ADMIN_PASSWORD not set - generated staff password: {$generated}");
        $this->command?->warn('Save it now; it will not be shown again.');

        return $generated;
    }
}
