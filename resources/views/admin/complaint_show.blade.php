@extends('layouts.app')

@section('content')

<div style="min-height:100vh; padding: 2.5rem 0 5rem; margin-top:40px;">
<div class="container" style="max-width:800px;">

    <div class="mb-4">
        <a href="{{ route('admin.complaints') }}" style="color:#6366f1;font-weight:600;font-size:0.85rem;text-decoration:none;">
            <i class="bi bi-arrow-left me-1"></i>Back to Complaints
        </a>
    </div>

    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:2rem;box-shadow:0 8px 30px rgba(0,0,0,0.06);">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h2 style="color:#0f172a;font-weight:800;margin-bottom:0.3rem;">{{ $complaint->subject }}</h2>
                <p style="color:#94a3b8;font-size:0.78rem;">Complaint #{{ $complaint->id }} · {{ $complaint->created_at->format('d M Y, g:i A') }}</p>
            </div>
            <span style="background:{{ $complaint->status === 'Open' ? 'rgba(239,68,68,0.1)' : ($complaint->status === 'Under Review' ? 'rgba(245,158,11,0.1)' : 'rgba(34,197,94,0.1)') }};color:{{ $complaint->status === 'Open' ? '#ef4444' : ($complaint->status === 'Under Review' ? '#d97706' : '#16a34a') }};border-radius:999px;font-size:0.75rem;font-weight:700;padding:4px 14px;">
                {{ $complaint->status }}
            </span>
        </div>

        {{-- Parties --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:0.85rem;">
                    <p style="color:#94a3b8;font-size:0.72rem;font-weight:700;text-transform:uppercase;margin-bottom:0.3rem;">Filed By</p>
                    <p style="color:#0f172a;font-weight:700;margin:0;">{{ $complaint->filer->name }}</p>
                    <p style="color:#64748b;font-size:0.78rem;margin:0;">{{ $complaint->filer->email }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:0.85rem;">
                    <p style="color:#94a3b8;font-size:0.72rem;font-weight:700;text-transform:uppercase;margin-bottom:0.3rem;">Against</p>
                    <p style="color:#0f172a;font-weight:700;margin:0;">{{ $complaint->accusedUser->name }}</p>
                    <p style="color:#64748b;font-size:0.78rem;margin:0;">{{ $complaint->accusedUser->email }}</p>
                </div>
            </div>
        </div>

        @if($complaint->contract)
            <div style="background:rgba(99,102,241,0.04);border:1px solid rgba(99,102,241,0.15);border-radius:14px;padding:0.75rem 1rem;margin-bottom:1.25rem;">
                <p style="color:#94a3b8;font-size:0.72rem;font-weight:700;text-transform:uppercase;margin-bottom:0.2rem;">Related Contract</p>
                <p style="color:#0f172a;font-weight:600;margin:0;">{{ $complaint->contract->subject }} · ৳{{ number_format($complaint->contract->salary) }}/mo</p>
            </div>
        @endif

        {{-- Description --}}
        <div style="margin-bottom:1.5rem;">
            <p style="color:#94a3b8;font-size:0.72rem;font-weight:700;text-transform:uppercase;margin-bottom:0.5rem;">Description</p>
            <p style="color:#0f172a;font-size:0.9rem;line-height:1.7;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:1rem;">{{ $complaint->description }}</p>
        </div>

        {{-- Admin Resolution --}}
        @if(in_array($complaint->status, ['Resolved', 'Dismissed']))
            <div style="border-top:2px solid #e2e8f0;padding-top:1.25rem;">
                <p style="color:#16a34a;font-weight:700;font-size:0.85rem;margin-bottom:0.5rem;">
                    <i class="bi bi-check-circle me-2"></i>{{ $complaint->status }} by {{ $complaint->resolver->name ?? 'Admin' }}
                    on {{ $complaint->resolved_at?->format('d M Y') }}
                </p>
                @if($complaint->admin_note)
                    <p style="color:#64748b;font-size:0.88rem;background:#f0fdf4;border:1px solid rgba(34,197,94,0.2);border-radius:12px;padding:0.75rem;">{{ $complaint->admin_note }}</p>
                @endif
            </div>
        @else
            {{-- Admin action form --}}
            <div style="border-top:2px solid #e2e8f0;padding-top:1.25rem;">
                <p style="font-weight:700;color:#0f172a;margin-bottom:0.75rem;">Admin Action</p>
                <form action="{{ route('admin.complaints.resolve', $complaint) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div style="margin-bottom:1rem;">
                        <label class="bab-label">Admin Note</label>
                        <textarea name="admin_note" rows="3" class="bab-input" placeholder="Add your resolution notes..."></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="status" value="Under Review"
                                class="bab-btn-secondary" style="background:#fef3c7;color:#d97706;border:1px solid #fde68a;flex:1;">
                            <i class="bi bi-search me-1"></i>Under Review
                        </button>
                        <button type="submit" name="status" value="Resolved"
                                class="bab-btn-primary" style="background:linear-gradient(135deg,#22c55e,#16a34a);flex:1;">
                            <i class="bi bi-check-lg me-1"></i>Resolve
                        </button>
                        <button type="submit" name="status" value="Dismissed"
                                class="bab-btn-secondary" style="flex:1;">
                            <i class="bi bi-x-lg me-1"></i>Dismiss
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

</div>
</div>

@endsection
