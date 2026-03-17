<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

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
            JobPostResponseSeeder::class,     
        ]);
        
    }
}