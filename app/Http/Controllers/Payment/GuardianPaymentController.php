<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\TuitionPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GuardianPaymentController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $guardian = Auth::user()->guardian;

        if (! $guardian) {
            abort(403, 'Guardian profile not found. Please complete your profile setup.');
        }

        $tuitionPayments = TuitionPayment::forGuardian($guardian->id)
            ->orderBy('payment_date', 'desc')
            ->get();

        $subscription = Subscription::forGuardian($guardian->id)
            ->latest()
            ->first();

        $subscriptionPayments = SubscriptionPayment::forGuardian($guardian->id)
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('payment.guardian', compact(
            'tuitionPayments', 'subscription', 'subscriptionPayments'
        ));
    }

    public function showPlan(): \Illuminate\View\View
    {
        $guardian = Auth::user()->guardian;

        if (! $guardian) {
            abort(403, 'Guardian profile not found. Please complete your profile setup.');
        }

        $plan = [
            'name' => 'Pro',
            'amount' => 500.00,
            'billing_cycle' => 'Monthly',
            'features' => [
                'Advanced tutor filtering by subject, class & location',
                'In-app messaging with tutors',
                'Priority job listings (appear at the top for tutors)',
                'Full CV access before shortlisting',
            ],
        ];

        $role = 'guardian';

        return view('payment.plan', compact('plan', 'role'));
    }

    public function confirmPlan(): \Illuminate\Http\RedirectResponse
    {
        $guardian = Auth::user()->guardian;

        if (! $guardian) {
            abort(403, 'Guardian profile not found. Please complete your profile setup.');
        }

        $activeSub = Subscription::forGuardian($guardian->id)
            ->where('status', 'active')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($activeSub) {
            return redirect()->route('guardian.payment.index')
                ->with('info', 'You already have an active subscription. Your current plan renews on ' . $activeSub->expires_at->format('d M Y') . '.');
        }

        session([
            'bkash_payment' => [
                'type' => 'subscription',
                'record_id' => null,
                'plan' => 'Pro',
                'role' => 'guardian',
                'payer_type' => 'guardian',
                'payer_id' => $guardian->id,
                'amount' => 500.00,
                'step' => 'phone',
                'phone' => null,
            ],
        ]);

        return redirect()->route('bkash.portal.phone');
    }

    public function initiatePayment(TuitionPayment $tuitionPayment): \Illuminate\Http\RedirectResponse
    {
        $guardian = Auth::user()->guardian;

        if (! $guardian) {
            abort(403, 'Guardian profile not found. Please complete your profile setup.');
        }

        if ((int) $tuitionPayment->guardian_id !== (int) $guardian->id) {
            abort(403, 'This payment does not belong to your account.');
        }

        if ($tuitionPayment->payment_status === 'paid') {
            return redirect()->route('guardian.payment.index')
                ->with('info', 'This payment has already been completed.');
        }

        $alreadyPaidThisMonth = TuitionPayment::forGuardian($guardian->id)
            ->where('tutor_id', $tuitionPayment->tutor_id)
            ->where('payment_status', 'paid')
            ->whereMonth('payment_date', Carbon::now()->month)
            ->whereYear('payment_date', Carbon::now()->year)
            ->exists();

        if ($alreadyPaidThisMonth) {
            return redirect()->route('guardian.payment.index')
                ->with('info', 'You have already made a payment for this tutor this month. Next payment is due in ' . Carbon::now()->endOfMonth()->diffInDays(Carbon::now()) . ' days.');
        }

        session([
            'bkash_payment' => [
                'type' => 'tuition',
                'record_id' => $tuitionPayment->id,
                'plan' => null,
                'role' => 'guardian',
                'payer_type' => 'guardian',
                'payer_id' => $guardian->id,
                'amount' => $tuitionPayment->amount,
                'step' => 'phone',
                'phone' => null,
            ],
        ]);

        return redirect()->route('bkash.portal.phone');
    }
}
