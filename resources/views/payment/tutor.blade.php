@extends('layouts.app')

@section('content')

<div style="min-height:100vh; padding: 2.5rem 0 5rem; margin-top:40px;">
<div class="container" style="max-width:1100px;">

    {{-- HEADER --}}
    <div class="mb-4">
        <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:700;">Book-a-Brain</p>
        <h1 style="color:#0f172a;font-size:2rem;font-weight:800;margin-bottom:0.25rem;">
            <i class="bi bi-credit-card me-2" style="color:#6366f1;"></i>Payment &amp; Subscription
        </h1>
        <p style="color:#64748b;margin:0;">Manage your subscription and review tuition engagements.</p>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div style="background:#f0fdf4;border:2px solid #bbf7d0;color:#16a34a;border-radius:14px;padding:0.9rem 1.25rem;margin-bottom:1.25rem;font-weight:600;font-size:0.88rem;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fef2f2;border:2px solid #fecaca;color:#ef4444;border-radius:14px;padding:0.9rem 1.25rem;margin-bottom:1.25rem;font-weight:600;font-size:0.88rem;">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div style="background:#eff6ff;border:2px solid #bfdbfe;color:#3b82f6;border-radius:14px;padding:0.9rem 1.25rem;margin-bottom:1.25rem;font-weight:600;font-size:0.88rem;">
            <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
        </div>
    @endif

    {{-- SECTION 1: SUBSCRIPTION STATUS --}}
    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1.5rem;">
        <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
            <i class="bi bi-shield-check me-2" style="color:#6366f1;"></i>Subscription Status
        </p>

        @if($subscription && $subscription->isActive())
            <div style="background:#f0fdf4;border:2px solid #bbf7d0;border-radius:16px;padding:1.25rem;">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <p style="font-size:1.1rem;font-weight:800;color:#0f172a;margin:0;">{{ $subscription->plan_name }} Plan</p>
                        <p style="color:#64748b;font-size:0.82rem;margin:0.2rem 0 0;">{{ ucfirst($subscription->billing_cycle) }} billing</p>
                    </div>
                    <span style="background:rgba(34,197,94,0.15);color:#16a34a;border-radius:999px;font-size:0.75rem;font-weight:700;padding:4px 14px;">Active</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4 col-6">
                        <p style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.3px;margin:0;">Amount</p>
                        <p style="font-weight:700;color:#0f172a;margin:0;">BDT {{ number_format($subscription->subscription_amount, 2) }}</p>
                    </div>
                    <div class="col-md-4 col-6">
                        <p style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.3px;margin:0;">Started</p>
                        <p style="font-weight:700;color:#0f172a;margin:0;">{{ $subscription->started_at->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-4 col-6">
                        <p style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.3px;margin:0;">Expires</p>
                        <p style="font-weight:700;color:#0f172a;margin:0;">{{ $subscription->expires_at->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-4 col-6">
                        <p style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.3px;margin:0;">Transaction ID</p>
                        <p style="font-weight:600;color:#0f172a;font-size:0.82rem;margin:0;">{{ $subscription->transaction_id }}</p>
                    </div>
                    <div class="col-md-4 col-6">
                        <p style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.3px;margin:0;">Method</p>
                        <p style="font-weight:700;color:#0f172a;margin:0;">{{ $subscription->formatted_method }}</p>
                    </div>
                </div>
            </div>

        @elseif($subscription && !$subscription->isActive())
            <div style="background:#fef2f2;border:2px solid #fecaca;border-radius:16px;padding:1.25rem;margin-bottom:1rem;">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <p style="font-size:1.1rem;font-weight:800;color:#0f172a;margin:0;">{{ $subscription->plan_name }} Plan</p>
                        <p style="color:#64748b;font-size:0.82rem;margin:0.2rem 0 0;">{{ ucfirst($subscription->billing_cycle) }} billing</p>
                    </div>
                    <span style="background:rgba(239,68,68,0.15);color:#ef4444;border-radius:999px;font-size:0.75rem;font-weight:700;padding:4px 14px;">Expired</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4 col-6">
                        <p style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.3px;margin:0;">Amount</p>
                        <p style="font-weight:700;color:#0f172a;margin:0;">BDT {{ number_format($subscription->subscription_amount, 2) }}</p>
                    </div>
                    <div class="col-md-4 col-6">
                        <p style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.3px;margin:0;">Started</p>
                        <p style="font-weight:700;color:#0f172a;margin:0;">{{ $subscription->started_at->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-4 col-6">
                        <p style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.3px;margin:0;">Expired On</p>
                        <p style="font-weight:700;color:#ef4444;margin:0;">{{ $subscription->expires_at->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-4 col-6">
                        <p style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.3px;margin:0;">Transaction ID</p>
                        <p style="font-weight:600;color:#0f172a;font-size:0.82rem;margin:0;">{{ $subscription->transaction_id }}</p>
                    </div>
                    <div class="col-md-4 col-6">
                        <p style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.3px;margin:0;">Method</p>
                        <p style="font-weight:700;color:#0f172a;margin:0;">{{ $subscription->formatted_method }}</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('tutor.subscribe.plan') }}"
               style="display:inline-block;background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border-radius:14px;padding:0.65rem 1.5rem;text-decoration:none;font-size:0.88rem;box-shadow:0 6px 20px rgba(99,102,241,0.3);">
                <i class="bi bi-shield-plus me-2"></i>Subscribe via Portal
            </a>

        @else
            <div style="background:#f8fafc;border:2px dashed #e2e8f0;border-radius:16px;padding:2rem;text-align:center;margin-bottom:1rem;">
                <i class="bi bi-shield-x" style="font-size:2rem;color:#cbd5e1;"></i>
                <p style="color:#64748b;margin:0.75rem 0 0;font-size:0.88rem;">You have no active subscription.</p>
            </div>
            <a href="{{ route('tutor.subscribe.plan') }}"
               style="display:inline-block;background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border-radius:14px;padding:0.65rem 1.5rem;text-decoration:none;font-size:0.88rem;box-shadow:0 6px 20px rgba(99,102,241,0.3);">
                <i class="bi bi-shield-plus me-2"></i>Subscribe via Portal
            </a>
        @endif
    </div>

    {{-- SECTION 2: SUBSCRIPTION PAYMENT HISTORY --}}
    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1.5rem;">
        <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
            <i class="bi bi-receipt me-2" style="color:#6366f1;"></i>Subscription Payment History
        </p>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:0.83rem;">
                <thead>
                    <tr style="border-bottom:2px solid #f1f5f9;">
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Transaction ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Billing Period</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Amount (BDT)</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Method</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptionPayments as $sp)
                    <tr style="border-bottom:1px solid #f8fafc;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.65rem 0.75rem;font-weight:600;color:#0f172a;font-size:0.8rem;">{{ $sp->transaction_id }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $sp->billing_period ?? '—' }}</td>
                        <td style="padding:0.65rem 0.75rem;font-weight:700;color:#0f172a;">BDT {{ number_format($sp->amount, 2) }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $sp->formatted_method }}</td>
                        <td style="padding:0.65rem 0.75rem;">
                            @if($sp->payment_status === 'paid')
                                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Paid</span>
                            @elseif($sp->payment_status === 'pending')
                                <span style="background:rgba(245,158,11,0.1);color:#d97706;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Pending</span>
                            @else
                                <span style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Failed</span>
                            @endif
                        </td>
                        <td style="padding:0.65rem 0.75rem;color:#94a3b8;font-size:0.78rem;">{{ $sp->payment_date?->format('d M Y') ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">No subscription payment history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 3: TUITION ENGAGEMENTS --}}
    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
        <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
            <i class="bi bi-book me-2" style="color:#6366f1;"></i>Tuition Engagements
        </p>
        <p style="color:#64748b;font-size:0.82rem;margin-bottom:1rem;">
            This section shows guardian tuition payment records linked to you as the tutor.
        </p>
        {{-- TODO: Disbursement amount to be shown once the earnings module is implemented --}}
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:0.83rem;">
                <thead>
                    <tr style="border-bottom:2px solid #f1f5f9;">
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Transaction ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Guardian ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Month</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Method</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Date</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tuitionEngagements as $te)
                    <tr style="border-bottom:1px solid #f8fafc;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.65rem 0.75rem;font-weight:600;color:#0f172a;font-size:0.8rem;">{{ $te->transaction_id }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $te->guardian_id }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $te->month_label ?? '—' }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $te->formatted_method }}</td>
                        <td style="padding:0.65rem 0.75rem;">
                            @if($te->payment_status === 'paid')
                                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Paid</span>
                            @elseif($te->payment_status === 'pending')
                                <span style="background:rgba(245,158,11,0.1);color:#d97706;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Pending</span>
                            @else
                                <span style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Failed</span>
                            @endif
                        </td>
                        <td style="padding:0.65rem 0.75rem;color:#94a3b8;font-size:0.78rem;">{{ $te->payment_date?->format('d M Y') ?? 'N/A' }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $te->description ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">No tuition engagements found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 4: HIRE & PAYMENT AUDIT LOG --}}
    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-top:1.5rem;">
        <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
            <i class="bi bi-journal-text me-2" style="color:#6366f1;"></i>Hire &amp; Payment Audit Log
        </p>
        @php
        $eventLabels = [
            'guardian_hired'          => 'Tutor hired',
            'tutor_confirmed'         => 'Tutor confirmed',
            'job_online'              => 'Engagement active',
            'tutor_declined'          => 'Tutor declined',
            'tutor_discarded'         => 'Applicant discarded',
            'cancellation_requested'  => 'Cancellation requested',
            'job_cancelled'           => 'Engagement cancelled',
            'payment_pending'         => 'Payment pending',
            'payment_completed'       => 'Payment completed',
        ];
        @endphp
        <div style="max-height:320px;overflow-y:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:0.83rem;">
                <thead style="position:sticky;top:0;background:white;z-index:1;">
                    <tr style="border-bottom:2px solid #f1f5f9;">
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Date</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Event</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Job ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Guardian ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Payment Status</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Amount (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditLogs as $log)
                    <tr style="border-bottom:1px solid #f8fafc;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.65rem 0.75rem;color:#94a3b8;font-size:0.78rem;">{{ $log->created_at->format('d M Y') }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#0f172a;font-weight:600;">{{ $eventLabels[$log->event_type] ?? $log->event_type }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $log->job_id ?? '—' }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $log->guardian_id ?? '—' }}</td>
                        <td style="padding:0.65rem 0.75rem;">
                            @if($log->payment_status === 'completed')
                                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Completed</span>
                            @else
                                <span style="background:rgba(245,158,11,0.1);color:#d97706;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Pending</span>
                            @endif
                        </td>
                        <td style="padding:0.65rem 0.75rem;font-weight:600;color:#0f172a;">
                            {{ $log->payment_amount ? 'BDT ' . number_format($log->payment_amount, 2) : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">No audit log entries yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

@endsection
