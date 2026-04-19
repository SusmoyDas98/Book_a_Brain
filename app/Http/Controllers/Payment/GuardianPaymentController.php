<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\TuitionPayment;
use Illuminate\Support\Facades\Auth;

class GuardianPaymentController extends Controller
{
    public function index()
    {
        $guardian = Auth::user()->guardian;

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
}
