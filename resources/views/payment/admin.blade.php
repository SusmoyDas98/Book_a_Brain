@extends('layouts.app')

@section('content')

<div style="min-height:100vh; padding: 2.5rem 0 5rem; margin-top:80px;">
<div class="container" style="max-width:1100px;">

    {{-- HEADER --}}
    <div class="mb-4">
        <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:700;">Book-a-Brain</p>
        <h1 style="color:#0f172a;font-size:2rem;font-weight:800;margin-bottom:0.25rem;">
            <i class="bi bi-credit-card me-2" style="color:#6366f1;"></i>Payment & Subscription Overview
        </h1>
        <p style="color:#64748b;margin:0;">Platform-wide subscription and payment records.</p>
    </div>

    {{-- IMPARTIALITY NOTICE --}}
    <p style="color:#94a3b8;font-style:italic;font-size:0.85rem;margin-bottom:1.5rem;">
        Monetary amounts are intentionally hidden in this view to ensure platform impartiality.
    </p>

    {{-- SECTION 1: SUBSCRIPTION RECORDS --}}
    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1.5rem;">
        <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
            <i class="bi bi-shield-check me-2" style="color:#6366f1;"></i>Subscription Records
        </p>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:0.83rem;">
                <thead>
                    <tr style="border-bottom:2px solid #f1f5f9;">
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">#</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Subscriber Type</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Subscriber ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Plan</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Billing Cycle</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Method</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Started</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Expires</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Transaction ID</th>
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
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $sub->formatted_method }}</td>
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
                        <td colspan="10" style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">No subscription records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 2: SUBSCRIPTION PAYMENT TRANSACTIONS --}}
    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1.5rem;">
        <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
            <i class="bi bi-receipt me-2" style="color:#6366f1;"></i>Subscription Payment Transactions
        </p>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:0.83rem;">
                <thead>
                    <tr style="border-bottom:2px solid #f1f5f9;">
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">#</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Transaction ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Subscriber Type</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Subscriber ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Billing Period</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Method</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptionPayments as $i => $sp)
                    <tr style="border-bottom:1px solid #f8fafc;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.65rem 0.75rem;color:#94a3b8;">{{ $i + 1 }}</td>
                        <td style="padding:0.65rem 0.75rem;font-weight:600;color:#0f172a;font-size:0.8rem;">{{ $sp->transaction_id }}</td>
                        <td style="padding:0.65rem 0.75rem;">
                            @if($sp->subscriber_type === 'guardian')
                                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Guardian</span>
                            @else
                                <span style="background:rgba(14,165,233,0.1);color:#0284c7;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Tutor</span>
                            @endif
                        </td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $sp->subscriber_id }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $sp->billing_period ?? '—' }}</td>
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
                        <td colspan="8" style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">No subscription payment transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 3: TUITION PAYMENT RECORDS --}}
    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
        <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
            <i class="bi bi-book me-2" style="color:#6366f1;"></i>Tuition Payment Records
        </p>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:0.83rem;">
                <thead>
                    <tr style="border-bottom:2px solid #f1f5f9;">
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">#</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Transaction ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Guardian ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Tutor ID</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Month</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Method</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Date</th>
                        <th style="text-align:left;padding:0.6rem 0.75rem;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tuitionPayments as $i => $tp)
                    <tr style="border-bottom:1px solid #f8fafc;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.65rem 0.75rem;color:#94a3b8;">{{ $i + 1 }}</td>
                        <td style="padding:0.65rem 0.75rem;font-weight:600;color:#0f172a;font-size:0.8rem;">{{ $tp->transaction_id }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $tp->guardian_id }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $tp->tutor_id }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $tp->month_label ?? '—' }}</td>
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $tp->formatted_method }}</td>
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
                        <td style="padding:0.65rem 0.75rem;color:#64748b;">{{ $tp->description ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">No tuition payment records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

@endsection
