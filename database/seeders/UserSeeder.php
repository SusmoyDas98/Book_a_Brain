<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Goku',
            'email' => 'goku@example.com',
            'password' => Hash::make('123'),
            'role' => 'admin'
        ]);

        // Standard users
        User::create([
            'name' => 'Naruto',
            'email' => 'naruto@example.com',
            'password' => Hash::make('123'),
            'role' => 'user'
        ]);

        User::create([
            'name' => 'Luffy',
            'email' => 'luffy@example.com',
            'password' => Hash::make('123'),
            'role' => 'user'
        ]);

        User::create([
            'name' => 'Sailor Moon',
            'email' => 'sailormoon@example.com',
            'password' => Hash::make('123'),
            'role' => 'user'
        ]);

        // Google OAuth users
        User::create([
            'name' => 'Totoro',
            'email' => 'totoro@example.com',
            'google_id' => '1000000001',
            'google_token' => null,
            'google_refresh_token' => null,
            'avatar' => 'https://example.com/totoro.png',
            'role' => 'user',
            'password' => Hash::make('123') // still needs a password for firstOrCreate logic
        ]);

        User::create([
            'name' => 'Kiki',
            'email' => 'kiki@example.com',
            'google_id' => '1000000002',
            'google_token' => null,
            'google_refresh_token' => null,
            'avatar' => 'https://example.com/kiki.png',
            'role' => 'user',
            'password' => Hash::make('123')
        ]);
    }
}