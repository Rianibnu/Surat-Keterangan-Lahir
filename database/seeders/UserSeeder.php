<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@rsx.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@rsx.com'],
            [
                'name' => 'Administrator RS',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Bidan 1
        $bidan1 = User::firstOrCreate(
            ['email' => 'bidan.siti@rsx.com'],
            [
                'name' => 'Bd. Siti Nurhaliza, Amd.Keb',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $bidan1->assignRole('bidan');

        // Bidan 2
        $bidan2 = User::firstOrCreate(
            ['email' => 'bidan.dewi@rsx.com'],
            [
                'name' => 'Bd. Dewi Lestari, Amd.Keb',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $bidan2->assignRole('bidan');

        // Bidan 3
        $bidan3 = User::firstOrCreate(
            ['email' => 'bidan.ratna@rsx.com'],
            [
                'name' => 'Bd. Ratna Sari, S.Keb',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $bidan3->assignRole('bidan');

        // Test User (from original seeder)
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $testUser->assignRole('super-admin');

        $this->command->info('Users seeded successfully!');
        $this->command->table(
            ['Name', 'Email', 'Role'],
            [
                [$superAdmin->name, $superAdmin->email, 'super-admin'],
                [$admin->name, $admin->email, 'admin'],
                [$bidan1->name, $bidan1->email, 'bidan'],
                [$bidan2->name, $bidan2->email, 'bidan'],
                [$bidan3->name, $bidan3->email, 'bidan'],
                [$testUser->name, $testUser->email, 'super-admin'],
            ]
        );
    }
}
