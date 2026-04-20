<?php

namespace Database\Seeders;

use App\Models\BkashAccount;
use App\Models\Guardian;
use App\Models\Tutor;
use Illuminate\Database\Seeder;

class BkashAccountSeeder extends Seeder
{
    public function run(): void
    {
        $guardians = Guardian::take(3)->get();
        $tutors = Tutor::take(3)->get();

        if ($guardians->isEmpty()) {
            $this->command->warn('BkashAccountSeeder: No guardians found — skipping guardian accounts.');
        }

        if ($tutors->isEmpty()) {
            $this->command->warn('BkashAccountSeeder: No tutors found — skipping tutor accounts.');
        }

        foreach ($guardians as $guardian) {
            $this->seedAccount('guardian', $guardian->id, '017'.str_pad($guardian->id.'1', 8, '0', STR_PAD_LEFT));
            $this->seedAccount('guardian', $guardian->id, '018'.str_pad($guardian->id.'2', 8, '0', STR_PAD_LEFT));
        }

        foreach ($tutors as $tutor) {
            $this->seedAccount('tutor', $tutor->tutor_id, '017'.str_pad($tutor->tutor_id.'1', 8, '0', STR_PAD_LEFT));
            $this->seedAccount('tutor', $tutor->tutor_id, '018'.str_pad($tutor->tutor_id.'2', 8, '0', STR_PAD_LEFT));
        }
    }

    private function seedAccount(string $type, int $holderId, string $phone): void
    {
        $exists = BkashAccount::where('account_holder_type', $type)
            ->where('account_holder_id', $holderId)
            ->where('phone_number', $phone)
            ->exists();

        if ($exists) {
            return;
        }

        BkashAccount::create([
            'account_holder_type' => $type,
            'account_holder_id' => $holderId,
            'phone_number' => $phone,
            'otp' => '1234',
            'password' => '1111',
            'is_active' => true,
        ]);
    }
}
