<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        $adminUser = User::create([
            'name' => 'Goku',
            'email' => 'admin@bookabrain.com',
            'password' => Hash::make('123'),
            'role' => 'admin'
        ]);

        Admin::create([
            'admin_id'    => $adminUser->id,
            'department'  => 'Platform',
            'permissions' => ['verify_tutors', 'manage_users', 'approve_cancellations'],
        ]);

        // Tutors (tutor_id=2, tutor_id=3 in TutorSeeder)
        User::create([
            'name' => 'Naruto',
            'email' => 'naruto@example.com',
            'password' => Hash::make('123'),
            'role' => 'tutor'
        ]);

        User::create([
            'name' => 'Luffy',
            'email' => 'luffy@example.com',
            'password' => Hash::make('123'),
            'role' => 'tutor'
        ]);

        // Guardians
        User::create([
            'name' => 'Sailor Moon',
            'email' => 'sailormoon@example.com',
            'password' => Hash::make('123'),
            'role' => 'guardian'
        ]);

        User::create([
            'name' => 'Totoro',
            'email' => 'totoro@example.com',
            'google_id' => '1000000001',
            'google_token' => null,
            'google_refresh_token' => null,
            'avatar' => 'https://example.com/totoro.png',
            'role' => 'guardian',
            'password' => Hash::make('123')
        ]);

        User::create([
            'name' => 'Kiki',
            'email' => 'kiki@example.com',
            'google_id' => '1000000002',
            'google_token' => null,
            'google_refresh_token' => null,
            'avatar' => 'https://example.com/kiki.png',
            'role' => 'guardian',
            'password' => Hash::make('123')
        ]);
    }
}