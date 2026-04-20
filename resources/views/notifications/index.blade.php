@extends('layouts.app')

@section('content')

<div style="min-height:100vh;padding:2.5rem 0 5rem;margin-top:40px;">
<div class="container" style="max-width:800px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:700;">Book-a-Brain</p>
            <h1 style="color:#0f172a;font-size:1.8rem;font-weight:800;margin-bottom:0.15rem;">
                <i class="bi bi-bell me-2" style="color:#6366f1;"></i>Notifications
            </h1>
            <p style="color:#64748b;margin:0;font-size:0.85rem;">{{ $notifications->count() }} notification{{ $notifications->count() !== 1 ? 's' : '' }}</p>
        </div>
        @if($notifications->where('is_read', false)->count() > 0)
        <form method="POST" action="{{ route('notifications.read.all') }}">
            @csrf
            <button type="submit"
                    style="background:white;color:#6366f1;font-weight:700;border:2px solid #6366f1;border-radius:12px;padding:0.6rem 1.25rem;font-size:0.85rem;cursor:pointer;">
                <i class="bi bi-check2-all me-1"></i>Mark all as read
            </button>
        </form>
        @endif
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div style="background:#f0fdf4;border:2px solid #bbf7d0;color:#16a34a;border-radius:14px;padding:0.9rem 1.25rem;margin-bottom:1.25rem;font-weight:600;font-size:0.88rem;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- NOTIFICATIONS LIST --}}
    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
        @forelse($notifications as $notification)
        <div style="padding:14px 16px;border-bottom:0.5px solid #e2e8f0;background:{{ $notification->is_read ? 'transparent' : 'rgba(99,102,241,0.04)' }};display:flex;justify-content:space-between;align-items:flex-start;gap:12px;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='{{ $notification->is_read ? 'transparent' : 'rgba(99,102,241,0.04)' }}'">
            <div style="display:flex;align-items:flex-start;gap:10px;flex:1;">
                {{-- Unread dot --}}
                @if(!$notification->is_read)
                <div style="width:8px;height:8px;background:#6366f1;border-radius:50%;flex-shrink:0;margin-top:6px;"></div>
                @else
                <div style="width:8px;height:8px;flex-shrink:0;"></div>
                @endif
                <div style="flex:1;">
                    <p style="font-weight:{{ $notification->is_read ? '500' : '700' }};margin:0 0 4px;color:#0f172a;font-size:0.88rem;">
                        {{ $notification->title }}
                    </p>
                    <p style="font-size:0.82rem;color:#64748b;margin:0 0 4px;">
                        {{ $notification->message }}
                    </p>
                    <p style="font-size:0.75rem;color:#94a3b8;margin:0;">
                        {{ $notification->created_at->diffForHumans() }}
                        @if($notification->related_job_id)
                            &nbsp;·&nbsp; Job #{{ $notification->related_job_id }}
                        @endif
                    </p>
                </div>
            </div>
            @if(!$notification->is_read)
            <form method="POST" action="{{ route('notifications.read', $notification->id) }}" style="flex-shrink:0;">
                @csrf
                <button type="submit"
                        style="background:none;border:1px solid #e2e8f0;color:#94a3b8;border-radius:8px;padding:3px 10px;font-size:0.75rem;cursor:pointer;white-space:nowrap;">
                    Mark read
                </button>
            </form>
            @else
            <span style="font-size:0.75rem;color:#cbd5e1;flex-shrink:0;">Read</span>
            @endif
        </div>
        @empty
        <div style="padding:3rem;text-align:center;">
            <i class="bi bi-bell-slash" style="font-size:2.5rem;color:#cbd5e1;"></i>
            <p style="color:#94a3b8;margin:0.75rem 0 0;font-size:0.88rem;">No notifications yet.</p>
        </div>
        @endforelse
    </div>

</div>
</div>

@endsection
