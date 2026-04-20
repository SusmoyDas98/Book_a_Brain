<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\BkashAccount;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\TuitionPayment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BkashPortalController extends Controller
{
    // ──────────────────────────────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────────────────────────────

    private function getPaymentSession(): array
    {
        $data = session('bkash_payment');
        if (! $data) {
            abort(403, 'No active payment session. Please start from your dashboard.');
        }

        return $data;
    }

    private function roleRedirect(string $role): RedirectResponse
    {
        return redirect()->route('dashboard');
    }

    // ──────────────────────────────────────────────────────────────────
    // STEP 1 — PHONE
    // ──────────────────────────────────────────────────────────────────

    public function showPhone(): View
    {
        $this->getPaymentSession();

        return view('bkash.phone');
    }

    public function submitPhone(Request $request): RedirectResponse
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'max:20'],
        ]);

        $session = $this->getPaymentSession();

        $account = BkashAccount::where('account_holder_type', $session['payer_type'])
            ->where('account_holder_id', $session['payer_id'])
            ->where('phone_number', $request->phone_number)
            ->where('is_active', true)
            ->first();

        if (! $account) {
            return back()->withErrors([
                'phone_number' => 'This number is not registered. Please use a registered bKash number.',
            ])->withInput();
        }

        $updated = $session;
        $updated['phone'] = $request->phone_number;
        $updated['step'] = 'otp';
        session(['bkash_payment' => $updated]);

        return redirect()->route('bkash.portal.otp');
    }

    // ──────────────────────────────────────────────────────────────────
    // STEP 2 — OTP
    // ──────────────────────────────────────────────────────────────────

    public function showOtp(): View
    {
        $session = $this->getPaymentSession();
        if ($session['step'] !== 'otp') {
            return view('bkash.phone');
        }

        return view('bkash.otp');
    }

    public function submitOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'max:10'],
        ]);

        $session = $this->getPaymentSession();

        if ($session['step'] !== 'otp') {
            return redirect()->route('bkash.portal.phone');
        }

        if ($request->otp !== '1234') {
            return back()->withErrors([
                'otp' => 'Incorrect verification code. Please try again.',
            ])->withInput();
        }

        $updated = $session;
        $updated['step'] = 'password';
        session(['bkash_payment' => $updated]);

        return redirect()->route('bkash.portal.password');
    }

    // ──────────────────────────────────────────────────────────────────
    // STEP 3 — PASSWORD / PIN
    // ──────────────────────────────────────────────────────────────────

    public function showPassword(): View
    {
        $session = $this->getPaymentSession();
        if ($session['step'] !== 'password') {
            return view('bkash.phone');
        }

        return view('bkash.password');
    }

    public function submitPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'max:10'],
        ]);

        $session = $this->getPaymentSession();

        if ($session['step'] !== 'password') {
            return redirect()->route('bkash.portal.phone');
        }

        if ($request->password !== '1111') {
            session()->forget('bkash_payment');

            return redirect()->route('bkash.portal.failed');
        }

        $this->processPayment($session);
        session()->forget('bkash_payment');

        return redirect()->route('bkash.portal.success');
    }

    // ──────────────────────────────────────────────────────────────────
    // PROCESS PAYMENT
    // ──────────────────────────────────────────────────────────────────

    private function processPayment(array $session): void
    {
        if ($session['type'] === 'tuition') {
            $payment = TuitionPayment::find($session['record_id']);
            if ($payment) {
                $payment->update([
                    'payment_status' => 'paid',
                    'payment_date' => Carbon::today()->toDateString(),
                ]);

                session()->flash('success', 'Your tuition payment was successful.');
            }

            return;
        }

        if ($session['type'] === 'subscription') {
            $subscriberType = $session['payer_type'];
            $subscriberId = $session['payer_id'];
            $plan = $session['plan'];
            $amount = $session['amount'];

            $txnId = 'TXN-'.Carbon::now()->format('Ymd').'-'.strtoupper(substr(uniqid(), -6));

            // Expire any currently active subscription
            Subscription::where('subscriber_type', $subscriberType)
                ->where('subscriber_id', $subscriberId)
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            $subscription = Subscription::create([
                'transaction_id' => $txnId,
                'subscriber_type' => $subscriberType,
                'subscriber_id' => $subscriberId,
                'plan_name' => $plan,
                'subscription_amount' => $amount,
                'currency' => 'BDT',
                'billing_cycle' => 'monthly',
                'payment_method' => 'portal',
                'status' => 'active',
                'started_at' => Carbon::today()->toDateString(),
                'expires_at' => Carbon::today()->addDays(30)->toDateString(),
            ]);

            $spTxnId = 'TXN-'.Carbon::now()->format('Ymd').'-'.strtoupper(substr(uniqid(), -6));

            SubscriptionPayment::create([
                'transaction_id' => $spTxnId,
                'subscriber_type' => $subscriberType,
                'subscriber_id' => $subscriberId,
                'subscription_id' => $subscription->id,
                'amount' => $amount,
                'currency' => 'BDT',
                'payment_method' => 'portal',
                'payment_status' => 'paid',
                'payment_date' => Carbon::today()->toDateString(),
                'billing_period' => Carbon::today()->format('F Y'),
            ]);

            session()->flash('success', 'Your subscription is now active.');
        }
    }

    // ──────────────────────────────────────────────────────────────────
    // OUTCOMES
    // ──────────────────────────────────────────────────────────────────

    public function success(): View
    {
        return view('bkash.success');
    }

    public function failed(): View
    {
        return view('bkash.failed');
    }

    public function cancelled(): View
    {
        return view('bkash.cancelled');
    }

    public function cancel(): RedirectResponse
    {
        session()->forget('bkash_payment');

        return redirect()->route('bkash.portal.cancelled');
    }
}
