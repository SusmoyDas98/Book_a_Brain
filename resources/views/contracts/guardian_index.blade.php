@extends('layouts.app')

@section('content')
<div style="min-height:100vh; padding:3rem 0 5rem;">
<div class="container" style="max-width:900px;">

    <div class="mb-4">
        <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:600;">Book-a-Brain</p>
        <h1 style="color:#0f172a;font-size:2rem;font-weight:700;margin-bottom:0.3rem;">My Tuition Contracts</h1>
        <p style="color:#64748b;">Track all your hired tutors and ongoing sessions.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3" style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.25);color:#4f46e5;border-radius:16px;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444;border-radius:16px;">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    @forelse($contracts as $contract)
        <div class="bab-card mb-3">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span style="font-weight:700;font-size:1.05rem;color:#0f172a;">{{ $contract->tutor->name }}</span>
                        @if($contract->status === 'ACTIVE')
                            <span style="background:rgba(34,197,94,0.1);color:#16a34a;border:1px solid rgba(34,197,94,0.3);border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">ACTIVE</span>
                        @elseif($contract->status === 'PENDING')
                            <span style="background:rgba(245,158,11,0.1);color:#d97706;border:1px solid rgba(245,158,11,0.3);border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">PENDING</span>
                        @else
                            <span style="background:rgba(100,116,139,0.1);color:#64748b;border:1px solid rgba(100,116,139,0.3);border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">ENDED</span>
                        @endif
                    </div>
                    <p style="color:#64748b;font-size:0.85rem;margin-bottom:0.25rem;">
                        <i class="bi bi-book me-1"></i>{{ $contract->subject }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-calendar3 me-1"></i>{{ $contract->schedule }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-currency-dollar me-1"></i>৳{{ number_format($contract->salary) }}/month
                    </p>
                    <p style="color:#94a3b8;font-size:0.78rem;margin:0;">
                        Started: {{ $contract->start_date->format('d M Y') }}
                        @if($contract->ended_at)
                            &nbsp;·&nbsp; Ended: {{ $contract->ended_at->format('d M Y') }}
                        @endif
                    </p>
                </div>
                <a href="{{ route('contracts.show', $contract) }}" class="bab-btn-primary" style="padding:0.6rem 1.4rem;font-size:0.85rem;">
                    View Details →
                </a>
            </div>
        </div>
    @empty
        <div class="bab-card text-center py-5">
            <i class="bi bi-file-earmark-x" style="font-size:2.5rem;color:#cbd5e1;"></i>
            <p style="color:#64748b;margin-top:1rem;">No contracts yet. Find a tutor and press <strong>Hire</strong> to get started.</p>
            <a href="{{ route('tutor_search_redirect') }}" class="bab-btn-primary mt-2">Find Tutors</a>
        </div>
    @endforelse

</div>
</div>
@endsection