@extends('layouts.app')

@section('content')

<div style="min-height:100vh; padding: 2.5rem 0 5rem; margin-top:80px;">
<div class="container" style="max-width:1100px;">

    {{-- HEADER --}}
    <div class="mb-4">
        <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:700;">Book-a-Brain</p>
        <h1 style="color:#0f172a;font-size:2rem;font-weight:800;margin-bottom:0.25rem;">
            <i class="bi bi-credit-card me-2" style="color:#6366f1;"></i>Payment &amp; Subscription Overview
        </h1>
        <p style="color:#64748b;margin:0;">Platform-wide subscription and payment records.</p>
    </div>

    {{-- IMPARTIALITY NOTICE --}}
    <p style="color:#94a3b8;font-style:italic;font-size:0.85rem;margin-bottom:1.5rem;">
        Monetary amounts are intentionally hidden in this view to ensure platform impartiality.
    </p>

    {{-- SECTION 1: SUBSCRIPTION HISTORY (scrollable) --}}
    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1.5rem;">
        <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
            <i class="bi bi-shield-check me-2" style="color:#6366f1;"></i>Subscription History
        </p>
        <div style="max-height:320px;overflow-y:auto;border:1px solid #f1f5f9;border-radius:12px;">
            <table style="width:100%;border-collapse:collapse;font-size:0.83rem;">
                <thead>
                    <tr style="border-bottom:2px solid #f1f5f9;">
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">#</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Subscriber Type</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Subscriber ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Plan</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Billing Cycle</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Status</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Started</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Expires</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Transaction ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $i => $sub)
                    <tr style="border-bottom:1px solid #f8fafc;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.65rem 0.75rem;color:#94a3b8;">{{ $i + 1 }}</td>
                        <td style="padding:0.65rem 0.75rem;">
                            @if($sub->subscriber_type === 'guardian')
                                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Guardian</span>
                            @else
                                <span style="background:rgba(14,165,233,0.1);color:#0284c7;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Tutor</span>
                            @endif
                        </td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $sub->subscriber_id }}</td>
                        <td style="padding:0.65rem 0.75rem;font-weight:600;color:#0f172a;">{{ $sub->plan_name }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ ucfirst($sub->billing_cycle) }}</td>
                        <td style="padding:0.65rem 0.75rem;">
                            @if($sub->status === 'active')
                                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Active</span>
                            @elseif($sub->status === 'expired')
                                <span style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Expired</span>
                            @else
                                <span style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Cancelled</span>
                            @endif
                        </td>
                        <td style="padding:0.65rem 0.75rem;color:#94a3b8;font-size:0.78rem;">{{ $sub->started_at->format('d M Y') }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#94a3b8;font-size:0.78rem;">{{ $sub->expires_at->format('d M Y') }}</td>
                        <td style="padding:0.65rem 0.75rem;font-size:0.78rem;color:#64748b;">{{ $sub->transaction_id }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">No subscription records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 2: PAYMENT HISTORY (scrollable) --}}
    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
        <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
            <i class="bi bi-book me-2" style="color:#6366f1;"></i>Payment History
        </p>
        <div style="max-height:320px;overflow-y:auto;border:1px solid #f1f5f9;border-radius:12px;">
            <table style="width:100%;border-collapse:collapse;font-size:0.83rem;">
                <thead>
                    <tr style="border-bottom:2px solid #f1f5f9;">
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">#</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Transaction ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Job ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Applicant (Tutor) ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Hired By (Guardian) ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Payment Status</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;position:sticky;top:0;background:white;z-index:1;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tuitionPayments as $i => $tp)
                    @php
                        $guardian = \App\Models\Guardian::find($tp->guardian_id);
                        $guardianUserId = $guardian ? $guardian->guardian_id : null;
                        $jobRecord = $guardianUserId
                            ? \App\Models\JobPost::where('guardian_id', $guardianUserId)->first()
                            : null;
                    @endphp
                    <tr style="border-bottom:1px solid #f8fafc;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.65rem 0.75rem;color:#94a3b8;">{{ $i + 1 }}</td>
                        <td style="padding:0.65rem 0.75rem;font-weight:600;color:#0f172a;font-size:0.8rem;">{{ $tp->transaction_id }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $jobRecord ? '#'.$jobRecord->id : '—' }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $tp->tutor_id }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $tp->guardian_id }}</td>
                        <td style="padding:0.65rem 0.75rem;">
                            @if($tp->payment_status === 'paid')
                                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Paid</span>
                            @elseif($tp->payment_status === 'pending')
                                <span style="background:rgba(245,158,11,0.1);color:#d97706;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Pending</span>
                            @else
                                <span style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Failed</span>
                            @endif
                        </td>
                        <td style="padding:0.65rem 0.75rem;color:#94a3b8;font-size:0.78rem;">{{ $tp->payment_date?->format('d M Y') ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">No tuition payment records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 4: HIRE & PAYMENT AUDIT LOG (Admin — no payment amounts) --}}
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
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Tutor ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adminAuditLogs as $log)
                    <tr style="border-bottom:1px solid #f8fafc;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.65rem 0.75rem;color:#94a3b8;font-size:0.78rem;">{{ $log->created_at->format('d M Y') }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#0f172a;font-weight:600;">{{ $eventLabels[$log->event_type] ?? $log->event_type }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $log->job_id ?? '—' }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $log->guardian_id ?? '—' }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $log->tutor_id ?? '—' }}</td>
                        <td style="padding:0.65rem 0.75rem;">
                            @if($log->payment_status === 'completed')
                                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Completed</span>
                            @else
                                <span style="background:rgba(245,158,11,0.1);color:#d97706;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Pending</span>
                            @endif
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
