<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset 2FA for existing user or create new
        $user = User::where('email', 'test@example.com')->first();
        
        if ($user) {
            // Remove 2FA
            $user->two_factor_secret = null;
            $user->two_factor_confirmed_at = null;
            $user->two_factor_recovery_codes = null;
            $user->save();
        } else {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
    }
}
