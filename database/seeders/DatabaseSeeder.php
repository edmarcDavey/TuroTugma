<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Add project test user plus two local DNHS users (IDs used as the 'email' field)
        // Ensure test user exists
        \App\Models\User::updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        // IT Coordinator
        \App\Models\User::updateOrCreate(
            ['email' => '300627-101'],
            ['name' => 'IT Coordinator', 'password' => bcrypt('TuroTugma@2025/DNHS')]
        );

        // Scheduler
        \App\Models\User::updateOrCreate(
            ['email' => '300627-201'],
            ['name' => 'Scheduler', 'password' => bcrypt('TuroTugma@2025/DNHS')]
        );
    }
}
