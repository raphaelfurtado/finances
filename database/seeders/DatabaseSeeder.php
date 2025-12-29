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
        // Create admin and test users first
        $this->call(AdminSeeder::class);

        // Create default categories for the admin user
        $admin = \App\Models\User::where('email', 'admin@financas-app.com')->first();
        if ($admin) {
            $this->call([
                CategorySeeder::class,
                TransactionSeeder::class,
            ]);
        }
    }
}
