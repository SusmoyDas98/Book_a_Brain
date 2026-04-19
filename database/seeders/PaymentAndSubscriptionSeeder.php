<?php

namespace Database\Seeders;

use App\Models\Guardian;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\TuitionPayment;
use App\Models\Tutor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentAndSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $counter = 1;
        $date = Carbon::now()->format('Ymd');

        $guardians = Guardian::take(3)->get();
        $tutors = Tutor::take(3)->get();

        if ($guardians->isEmpty()) {
            $this->command->warn('No guardians found. Skipping guardian seeding.');
        }

        if ($tutors->isEmpty()) {
            $this->command->warn('No tutors found. Skipping tutor seeding.');
        }

        // ── GUARDIAN LOOP ────────────────────────────────────────────────
        foreach ($guardians as $guardian) {

            // --- TUITION PAYMENTS ---
            if ($tutors->isNotEmpty()) {

                // Record 1: paid
                $txnId = 'TXN-'.$date.'-'.str_pad($counter++, 4, '0', STR_PAD_LEFT);
                if (! TuitionPayment::where('transaction_id', $txnId)->exists()) {
                    TuitionPayment::create([
                        'transaction_id' => $txnId,
                        'guardian_id' => $guardian->id,
                        'tutor_id' => $tutors->first()->tutor_id,
                        'amount' => 4000.00,
                        'currency' => 'BDT',
                        'payment_method' => 'portal',
                        'payment_status' => 'paid',
                        'payment_date' => Carbon::now()->subDays(30)->toDateString(),
                        'month_label' => Carbon::now()->subMonth()->format('F Y'),
                        'description' => 'Tuition fee - Month 1',
                    ]);
                }

                // Record 2: pending
                $txnId = 'TXN-'.$date.'-'.str_pad($counter++, 4, '0', STR_PAD_LEFT);
                if (! TuitionPayment::where('transaction_id', $txnId)->exists()) {
                    TuitionPayment::create([
                        'transaction_id' => $txnId,
                        'guardian_id' => $guardian->id,
                        'tutor_id' => $tutors->first()->tutor_id,
                        'amount' => 4000.00,
                        'currency' => 'BDT',
                        'payment_method' => 'portal',
                        'payment_status' => 'pending',
                        'payment_date' => null,
                        'month_label' => Carbon::now()->format('F Y'),
                        'description' => 'Tuition fee - Month 2',
                    ]);
                }
            }

            // --- GUARDIAN SUBSCRIPTION ---
            $txnId = 'TXN-'.$date.'-'.str_pad($counter++, 4, '0', STR_PAD_LEFT);
            if (! Subscription::where('subscriber_type', 'guardian')
                ->where('subscriber_id', $guardian->id)
                ->exists()) {
                $guardianSubscription = Subscription::create([
                    'transaction_id' => $txnId,
                    'subscriber_type' => 'guardian',
                    'subscriber_id' => $guardian->id,
                    'plan_name' => 'Pro',
                    'subscription_amount' => 500.00,
                    'currency' => 'BDT',
                    'billing_cycle' => 'monthly',
                    'payment_method' => 'portal',
                    'status' => 'active',
                    'started_at' => Carbon::now()->subDays(30)->toDateString(),
                    'expires_at' => Carbon::now()->addDays(30)->toDateString(),
                ]);
            } else {
                $guardianSubscription = Subscription::where('subscriber_type', 'guardian')
                    ->where('subscriber_id', $guardian->id)
                    ->latest()
                    ->first();
            }

            // --- GUARDIAN SUBSCRIPTION PAYMENT ---
            $txnId = 'TXN-'.$date.'-'.str_pad($counter++, 4, '0', STR_PAD_LEFT);
            if (! SubscriptionPayment::where('transaction_id', $txnId)->exists()) {
                SubscriptionPayment::create([
                    'transaction_id' => $txnId,
                    'subscriber_type' => 'guardian',
                    'subscriber_id' => $guardian->id,
                    'subscription_id' => $guardianSubscription->id,
                    'amount' => 500.00,
                    'currency' => 'BDT',
                    'payment_method' => 'portal',
                    'payment_status' => 'paid',
                    'payment_date' => Carbon::now()->subDays(30)->toDateString(),
                    'billing_period' => Carbon::now()->subMonth()->format('F Y'),
                ]);
            }
        }

        // ── TUTOR LOOP ──────────────────────────────────────────────────
        $tutorStatuses = ['active', 'expired', 'active'];

        foreach ($tutors as $index => $tutor) {
            $status = $tutorStatuses[$index] ?? 'active';
            $expiresAt = ($status === 'active')
                ? Carbon::now()->addDays(30)->toDateString()
                : Carbon::now()->subDays(5)->toDateString();

            // --- TUTOR SUBSCRIPTION ---
            $txnId = 'TXN-'.$date.'-'.str_pad($counter++, 4, '0', STR_PAD_LEFT);
            if (! Subscription::where('subscriber_type', 'tutor')
                ->where('subscriber_id', $tutor->tutor_id)
                ->exists()) {
                $tutorSubscription = Subscription::create([
                    'transaction_id' => $txnId,
                    'subscriber_type' => 'tutor',
                    'subscriber_id' => $tutor->tutor_id,
                    'plan_name' => 'Basic',
                    'subscription_amount' => 300.00,
                    'currency' => 'BDT',
                    'billing_cycle' => 'monthly',
                    'payment_method' => 'portal',
                    'status' => $status,
                    'started_at' => Carbon::now()->subDays(30)->toDateString(),
                    'expires_at' => $expiresAt,
                ]);
            } else {
                $tutorSubscription = Subscription::where('subscriber_type', 'tutor')
                    ->where('subscriber_id', $tutor->tutor_id)
                    ->latest()
                    ->first();
            }

            // --- TUTOR SUBSCRIPTION PAYMENT ---
            $txnId = 'TXN-'.$date.'-'.str_pad($counter++, 4, '0', STR_PAD_LEFT);
            if (! SubscriptionPayment::where('transaction_id', $txnId)->exists()) {
                SubscriptionPayment::create([
                    'transaction_id' => $txnId,
                    'subscriber_type' => 'tutor',
                    'subscriber_id' => $tutor->tutor_id,
                    'subscription_id' => $tutorSubscription->id,
                    'amount' => 300.00,
                    'currency' => 'BDT',
                    'payment_method' => 'portal',
                    'payment_status' => 'paid',
                    'payment_date' => Carbon::now()->subDays(30)->toDateString(),
                    'billing_period' => Carbon::now()->subMonth()->format('F Y'),
                ]);
            }
        }
    }
}
