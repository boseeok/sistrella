<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Creates the default staff accounts. Credentials are intentionally simple
 * for local/demo use — change them in production immediately.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@crochetstore.test'],
            [
                'name'              => 'Store Admin',
                'password'          => Hash::make('password'),
                'phone'             => '977-9761612457',
                'is_active'         => true,
                'email_verified_at' => now(),
            ],
        );
        $admin->syncRoles(['admin']);

        $manager = User::updateOrCreate(
            ['email' => 'manager@crochetstore.test'],
            [
                'name'              => 'Store Manager',
                'password'          => Hash::make('password'),
                'phone'             => '977-9761612457',
                'is_active'         => true,
                'email_verified_at' => now(),
            ],
        );
        $manager->syncRoles(['manager']);

        $staff = User::updateOrCreate(
            ['email' => 'staff@crochetstore.test'],
            [
                'name'              => 'Store Staff',
                'password'          => Hash::make('password'),
                'phone'             => '977-9761612457',
                'is_active'         => true,
                'email_verified_at' => now(),
            ],
        );
        $staff->syncRoles(['staff']);
    }
}
