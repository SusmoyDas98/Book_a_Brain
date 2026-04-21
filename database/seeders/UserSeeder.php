<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@bookabrain.com'],
            ['name' => 'Goku', 'password' => Hash::make('123'), 'role' => 'admin']
        );

        if (! Admin::where('admin_id', $adminUser->id)->exists()) {
            Admin::create([
                'admin_id' => $adminUser->id,
                'department' => 'Platform',
                'permissions' => ['verify_tutors', 'manage_users', 'approve_cancellations'],
            ]);
        }

        // Tutors (tutor_id=2, tutor_id=3 in TutorSeeder)
        User::firstOrCreate(
            ['email' => 'naruto@example.com'],
            ['name' => 'Naruto', 'password' => Hash::make('123'), 'role' => 'tutor']
        );

        User::firstOrCreate(
            ['email' => 'luffy@example.com'],
            ['name' => 'Luffy', 'password' => Hash::make('123'), 'role' => 'tutor']
        );

        // Guardians
        User::firstOrCreate(
            ['email' => 'sailormoon@example.com'],
            ['name' => 'Sailor Moon', 'password' => Hash::make('123'), 'role' => 'guardian']
        );

        User::firstOrCreate(
            ['email' => 'totoro@example.com'],
            [
                'name' => 'Totoro', 'password' => Hash::make('123'), 'role' => 'guardian',
                'google_id' => '1000000001', 'avatar' => 'https://example.com/totoro.png',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kiki@example.com'],
            [
                'name' => 'Kiki', 'password' => Hash::make('123'), 'role' => 'guardian',
                'google_id' => '1000000002', 'avatar' => 'https://example.com/kiki.png',
            ]
        );
    }
}
