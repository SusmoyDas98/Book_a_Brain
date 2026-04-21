<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Guardian;

class GuardianSeeder extends Seeder
{
    public function run(): void
    {
        // Get users that are allowed to be guardians
        $users = User::whereIn('email', [
            'luffy@example.com',
            'sailormoon@example.com',
            'totoro@example.com',
            'kiki@example.com',
        ])->get();

        foreach ($users as $user) {
            Guardian::firstOrCreate(
                ['guardian_id' => $user->id],
                [
                    'name'            => $user->name,
                    'email'           => $user->email,
                    'contact_no'      => '+8801' . rand(100000000, 999999999),
                    'gender'          => rand(0, 1) ? 'Male' : 'Female',
                    'profile_picture' => $user->avatar ?? null,
                    'nid_card'        => null,
                    'address'         => '123 Example Street, Dhaka',
                    'location'        => json_encode([
                        'lat' => 23.8103 + (rand(-100, 100) / 10000),
                        'lng' => 90.4125 + (rand(-100, 100) / 10000),
                    ]),
                ]
            );
        }
    }
}