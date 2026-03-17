@extends('layouts.app')

@section('content')
<div style="min-height:100vh; padding:3rem 0 5rem;">
<div class="container" style="max-width:900px;">

    <div class="mb-4">
        <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:600;">Book-a-Brain</p>
        <h1 style="color:#0f172a;font-size:2rem;font-weight:700;margin-bottom:0.3rem;">My Tuition Requests</h1>
        <p style="color:#64748b;">Manage incoming hire requests and active contracts.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3" style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.25);color:#4f46e5;border-radius:16px;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- PENDING REQUESTS --}}
    @php $pending = $contracts->where('status','PENDING'); @endphp
    @if($pending->count())
        <p style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.3em;color:#d97706;font-weight:700;margin-bottom:0.75rem;">
            <i class="bi bi-hourglass-split me-1"></i>Pending Requests ({{ $pending->count() }})
        </p>
        @foreach($pending as $contract)
            <div class="bab-card mb-3" style="border-left:4px solid #f59e0b;">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <p style="font-weight:700;font-size:1.05rem;color:#0f172a;margin-bottom:0.25rem;">
                            {{ $contract->guardian->name }}
                            <span style="font-size:0.75rem;color:#64748b;font-weight:400;">wants to hire you</span>
                        </p>
                        <p style="color:#64748b;font-size:0.85rem;margin-bottom:0.25rem;">
                            <i class="bi bi-book me-1"></i>{{ $contract->subject }}
                            &nbsp;·&nbsp;
                            <i class="bi bi-calendar3 me-1"></i>{{ $contract->schedule }}
                            &nbsp;·&nbsp;
                            ৳{{ number_format($contract->salary) }}/month
                        </p>
                        <p style="color:#94a3b8;font-size:0.78rem;margin:0;">
                            Start date: {{ $contract->start_date->format('d M Y') }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <form action="{{ route('contracts.accept', $contract) }}" method="POST">
                            @csrf
                            <button type="submit" class="bab-btn-primary" style="padding:0.55rem 1.2rem;font-size:0.85rem;">
                                <i class="bi bi-check-lg me-1"></i>Accept
                            </button>
                        </form>
                        <form action="{{ route('contracts.decline', $contract) }}" method="POST">
                            @csrf
                            <button type="submit" class="bab-btn-secondary" style="padding:0.55rem 1.2rem;font-size:0.85rem;"
                                onclick="return confirm('Are you sure you want to decline this request?')">
                                Decline
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- ACTIVE & ENDED CONTRACTS --}}
    @php $others = $contracts->whereIn('status',['ACTIVE','ENDED']); @endphp
    @if($others->count())
        <p style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:700;margin-top:1.5rem;margin-bottom:0.75rem;">
            Contracts
        </p>
        @foreach($others as $contract)
            <div class="bab-card mb-3">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span style="font-weight:700;font-size:1.05rem;color:#0f172a;">{{ $contract->guardian->name }}</span>
                            @if($contract->status === 'ACTIVE')
                                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border:1px solid rgba(34,197,94,0.3);border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">ACTIVE</span>
                            @else
                                <span style="background:rgba(100,116,139,0.1);color:#64748b;border:1px solid rgba(100,116,139,0.3);border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">ENDED</span>
                            @endif
                        </div>
                        <p style="color:#64748b;font-size:0.85rem;margin-bottom:0.25rem;">
                            <i class="bi bi-book me-1"></i>{{ $contract->subject }}
                            &nbsp;·&nbsp;
                            <i class="bi bi-calendar3 me-1"></i>{{ $contract->schedule }}
                            &nbsp;·&nbsp;
                            ৳{{ number_format($contract->salary) }}/month
                        </p>
                    </div>
                    <a href="{{ route('contracts.show', $contract) }}" class="bab-btn-primary" style="padding:0.6rem 1.4rem;font-size:0.85rem;">
                        View & Log Sessions →
                    </a>
                </div>
            </div>
        @endforeach
    @endif

    @if($contracts->isEmpty())
        <div class="bab-card text-center py-5">
            <i class="bi bi-inbox" style="font-size:2.5rem;color:#cbd5e1;"></i>
            <p style="color:#64748b;margin-top:1rem;">No hire requests yet.</p>
        </div>
    @endif

</div>
</div>
@endsection