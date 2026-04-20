<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\TuitionPayment;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $tuitionPayments = TuitionPayment::adminSafe()
            ->orderBy('payment_date', 'desc')
            ->get();

        $subscriptions = Subscription::adminSafe()
            ->orderBy('created_at', 'desc')
            ->get();

        $subscriptionPayments = SubscriptionPayment::adminSafe()
            ->orderBy('payment_date', 'desc')
            ->get();

        $adminAuditLogs = AuditLog::adminSafe()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payment.admin', compact(
            'tuitionPayments', 'subscriptions', 'subscriptionPayments', 'adminAuditLogs'
        ));
    }
}
