@extends('layouts.app')

@section('content')
@php
    $contracts = \App\Models\TuitionContract::with(['tutor','guardian'])->latest()->get();
@endphp

<div style="min-height:100vh;padding:2.5rem 0 5rem;">
<div class="container" style="max-width:1200px;">

    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:700;">Admin · Book-a-Brain</p>
            <h1 style="color:#0f172a;font-size:2rem;font-weight:800;margin-bottom:0.2rem;">All Contracts</h1>
            <p style="color:#64748b;margin:0;">{{ $contracts->count() }} total contracts on the platform.</p>
        </div>
        <a href="{{ route('dashboard') }}" style="background:#f1f5f9;color:#6366f1;font-weight:700;border-radius:12px;padding:0.6rem 1.25rem;text-decoration:none;font-size:0.85rem;border:2px solid #e2e8f0;">
            ← Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="mb-3 px-4 py-3" style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.25);color:#4f46e5;border-radius:16px;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Tutor</th>
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Guardian</th>
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Subject</th>
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Salary</th>
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Start Date</th>
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                        <th style="padding:1rem 1.25rem;text-align:left;color:#64748b;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contracts as $contract)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:1rem 1.25rem;">
                            <p style="font-weight:700;color:#0f172a;margin:0;">{{ $contract->tutor->name }}</p>
                            <p style="color:#94a3b8;font-size:0.75rem;margin:0;">{{ $contract->tutor->email }}</p>
                        </td>
                        <td style="padding:1rem 1.25rem;">
                            <p style="font-weight:600;color:#1e293b;margin:0;">{{ $contract->guardian->name }}</p>
                            <p style="color:#94a3b8;font-size:0.75rem;margin:0;">{{ $contract->guardian->email }}</p>
                        </td>
                        <td style="padding:1rem 1.25rem;color:#64748b;">{{ $contract->subject }}</td>
                        <td style="padding:1rem 1.25rem;font-weight:700;color:#0f172a;">৳{{ number_format($contract->salary) }}</td>
                        <td style="padding:1rem 1.25rem;color:#94a3b8;font-size:0.8rem;">{{ $contract->start_date->format('d M Y') }}</td>
                        <td style="padding:1rem 1.25rem;">
                            @if($contract->status === 'ACTIVE')
                                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.72rem;font-weight:700;padding:3px 12px;">ACTIVE</span>
                            @elseif($contract->status === 'PENDING')
                                <span style="background:rgba(245,158,11,0.1);color:#d97706;border-radius:999px;font-size:0.72rem;font-weight:700;padding:3px 12px;">PENDING</span>
                            @else
                                <span style="background:#f1f5f9;color:#94a3b8;border-radius:999px;font-size:0.72rem;font-weight:700;padding:3px 12px;">ENDED</span>
                            @endif
                        </td>
                        <td style="padding:1rem 1.25rem;">
                            <form action="{{ route('admin.contract.payment', $contract) }}" method="POST" class="m-0">
                                @csrf
                                @if($contract->is_paid)
                                    <input type="hidden" name="is_paid" value="0">
                                    <button type="submit"
                                        style="background:rgba(34,197,94,0.1);color:#16a34a;border:1px solid rgba(34,197,94,0.3);border-radius:10px;padding:4px 14px;font-size:0.78rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:5px;">
                                        <i class="bi bi-check-circle-fill"></i> Paid
                                    </button>
                                @else
                                    <input type="hidden" name="is_paid" value="1">
                                    <button type="submit"
                                        style="background:#f1f5f9;color:#94a3b8;border:1px solid #e2e8f0;border-radius:10px;padding:4px 14px;font-size:0.78rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:5px;">
                                        <i class="bi bi-circle"></i> Unpaid
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding:3rem;text-align:center;color:#94a3b8;">No contracts yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
@endsection
