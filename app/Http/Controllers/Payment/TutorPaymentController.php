<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\TuitionPayment;
use App\Models\Tutor;
use Illuminate\Support\Facades\Auth;

class TutorPaymentController extends Controller
{
    public function index()
    {
        $tutor = Tutor::where('tutor_id', Auth::id())->first();

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
}
