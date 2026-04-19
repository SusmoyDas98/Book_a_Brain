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
        // Optional: keep your factory example
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Call your custom UserSeeder
        $this->call([
            UserSeeder::class,
            TutorSeeder::class,
            TutorProfileSeeder::class,
            GuardianSeeder::class,
            JobPostResponseSeeder::class,
            PaymentAndSubscriptionSeeder::class,
        ]);

    }
}
