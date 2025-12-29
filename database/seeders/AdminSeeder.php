<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@financas-app.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'is_premium' => true,
            'premium_activated_at' => now(),
            'premium_expires_at' => now()->addYears(10), // 10 years premium
        ]);

        // Create test users - 5 premium, 10 free
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Usuário Premium $i",
                'email' => "premium$i@test.com",
                'password' => Hash::make('password'),
                'is_premium' => true,
                'premium_activated_at' => now()->subDays(rand(1, 30)),
                'premium_expires_at' => now()->addDays(rand(1, 30)),
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Usuário Grátis $i",
                'email' => "free$i@test.com",
                'password' => Hash::make('password'),
                'is_premium' => false,
            ]);
        }
    }
}
