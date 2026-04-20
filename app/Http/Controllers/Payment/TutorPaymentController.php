<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\TuitionPayment;
use App\Models\Tutor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TutorPaymentController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $tutor = Tutor::where('tutor_id', Auth::id())->first();

        if (! $tutor) {
            abort(403, 'Tutor profile not found. Please complete your profile setup.');
        }

        $tuitionEngagements = TuitionPayment::forTutor($tutor->tutor_id)
            ->orderBy('payment_date', 'desc')
            ->get();

        $subscription = Subscription::forTutor($tutor->tutor_id)
            ->latest()
            ->first();

        $subscriptionPayments = SubscriptionPayment::forTutor($tutor->tutor_id)
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('payment.tutor', compact(
            'tuitionEngagements', 'subscription', 'subscriptionPayments'
        ));
    }

    public function showPlan(): \Illuminate\View\View
    {
        $tutor = Tutor::where('tutor_id', Auth::id())->first();

        if (! $tutor) {
            abort(403, 'Tutor profile not found. Please complete your profile setup.');
        }

        $plan = [
            'name' => 'Basic',
            'amount' => 200.00,
            'billing_cycle' => 'Monthly',
            'features' => [
                'Profile visibility boost in guardian search results',
                'In-app messaging with hired guardians',
                'Application status tracking in real time',
            ],
        ];

        $role = 'tutor';

        $activeSubscription = Subscription::forTutor($tutor->tutor_id)
            ->where('status', 'active')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        return view('payment.plan', compact('plan', 'role', 'activeSubscription'));
    }

    public function confirmPlan(): \Illuminate\Http\RedirectResponse
    {
        $tutor = Tutor::where('tutor_id', Auth::id())->first();

        if (! $tutor) {
            abort(403, 'Tutor profile not found. Please complete your profile setup.');
        }

        $activeSub = Subscription::forTutor($tutor->tutor_id)
            ->where('status', 'active')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($activeSub) {
            return redirect()->route('tutor.payment.index')
                ->with('info', 'You already have an active subscription. Your current plan renews on ' . $activeSub->expires_at->format('d M Y') . '.');
        }

        session([
            'bkash_payment' => [
                'type' => 'subscription',
                'record_id' => null,
                'plan' => 'Basic',
                'role' => 'tutor',
                'payer_type' => 'tutor',
                'payer_id' => $tutor->tutor_id,
                'amount' => 200.00,
                'step' => 'phone',
                'phone' => null,
            ],
        ]);

        return redirect()->route('bkash.portal.phone');
    }
}
