@extends('layouts.app')

@section('content')

<div style="min-height:100vh; padding: 2.5rem 0 5rem; margin-top:40px;">
<div class="container" style="max-width:1000px;">

    <div class="mb-4">
        <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#ef4444;font-weight:700;">Admin Panel</p>
        <h1 style="color:#0f172a;font-size:1.8rem;font-weight:800;margin-bottom:0.3rem;">Complaints</h1>
        <p style="color:#64748b;font-size:0.88rem;">Manage user-submitted complaints and disputes.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3" style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.25);color:#4f46e5;border-radius:16px;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @php
            $statusCounts = [
                'Open' => $complaints->where('status', 'Open')->count(),
                'Under Review' => $complaints->where('status', 'Under Review')->count(),
                'Resolved' => $complaints->where('status', 'Resolved')->count(),
                'Dismissed' => $complaints->where('status', 'Dismissed')->count(),
            ];
        @endphp
        @foreach($statusCounts as $status => $count)
            <div class="col-6 col-md-3">
                <div style="background:white;border:2px solid #e2e8f0;border-radius:16px;padding:1rem;text-align:center;">
                    <p style="font-size:1.75rem;font-weight:800;color:#0f172a;margin:0;">{{ $count }}</p>
                    <p style="color:#94a3b8;font-size:0.75rem;font-weight:700;margin:0;">{{ $status }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Table --}}
    @forelse($complaints as $complaint)
        <div class="bab-card mb-3" style="border-left:4px solid {{ $complaint->status === 'Open' ? '#ef4444' : ($complaint->status === 'Under Review' ? '#f59e0b' : '#22c55e') }};">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span style="font-weight:700;color:#0f172a;">{{ $complaint->subject }}</span>
                        <span style="background:{{ $complaint->status === 'Open' ? 'rgba(239,68,68,0.1)' : ($complaint->status === 'Under Review' ? 'rgba(245,158,11,0.1)' : 'rgba(34,197,94,0.1)') }};color:{{ $complaint->status === 'Open' ? '#ef4444' : ($complaint->status === 'Under Review' ? '#d97706' : '#16a34a') }};border-radius:999px;font-size:0.68rem;font-weight:700;padding:2px 10px;">
                            {{ $complaint->status }}
                        </span>
                    </div>
                    <p style="color:#64748b;font-size:0.82rem;margin:0.2rem 0 0;">
                        Filed by <strong>{{ $complaint->filer->name }}</strong>
                        against <strong>{{ $complaint->accusedUser->name }}</strong>
                        @if($complaint->contract)
                            · {{ $complaint->contract->subject }}
                        @endif
                    </p>
                    <p style="color:#cbd5e1;font-size:0.72rem;margin:0.2rem 0 0;">{{ $complaint->created_at->diffForHumans() }}</p>
                </div>
                <a href="{{ route('admin.complaints.show', $complaint) }}" class="bab-btn-primary" style="padding:0.5rem 1rem;font-size:0.82rem;">
                    View →
                </a>
            </div>
        </div>
    @empty
        <div class="bab-card text-center py-5">
            <i class="bi bi-shield-check" style="font-size:2.5rem;color:#22c55e;"></i>
            <p style="color:#64748b;margin-top:1rem;">No complaints filed. Great!</p>
        </div>
    @endforelse

</div>
</div>

@endsection
