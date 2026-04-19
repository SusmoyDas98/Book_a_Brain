@extends('layouts.app')

@section('content')

<div style="min-height:100vh; padding: 2.5rem 0 5rem; margin-top:40px;">
<div class="container" style="max-width:800px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:700;">Book-a-Brain</p>
            <h1 style="color:#0f172a;font-size:1.8rem;font-weight:800;margin-bottom:0;">Messages</h1>
            <p style="color:#64748b;margin:0;font-size:0.88rem;">Chat with your {{ strtolower(Auth::user()->role) === 'tutor' ? 'guardians' : 'tutors' }}</p>
        </div>
    </div>

    {{-- CONVERSATIONS LIST --}}
    @forelse($conversations as $conversation)
        @php
            $other = $conversation->otherUser(Auth::id());
            $lastMsg = $conversation->latestMessage;
        @endphp
        <a href="{{ route('messages.show', $conversation) }}"
           style="display:flex;align-items:center;gap:1rem;background:white;border:2px solid {{ $conversation->unread_count > 0 ? 'rgba(99,102,241,0.4)' : '#e2e8f0' }};border-radius:20px;padding:1rem 1.25rem;margin-bottom:0.75rem;text-decoration:none;transition:0.2s;box-shadow:0 4px 15px rgba(0,0,0,0.05);"
           onmouseover="this.style.borderColor='#6366f1';this.style.transform='translateY(-2px)'"
           onmouseout="this.style.borderColor='{{ $conversation->unread_count > 0 ? 'rgba(99,102,241,0.4)' : '#e2e8f0' }}';this.style.transform='translateY(0)'">

            {{-- Avatar --}}
            <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:white;font-weight:800;font-size:1.1rem;">
                {{ strtoupper(substr($other->name ?? '?', 0, 1)) }}
            </div>

            {{-- Content --}}
            <div style="flex:1;min-width:0;">
                <div class="d-flex justify-content-between align-items-center">
                    <p style="font-weight:700;color:#0f172a;margin:0;font-size:0.92rem;">{{ $other->name }}</p>
                    @if($lastMsg)
                        <span style="color:#94a3b8;font-size:0.72rem;white-space:nowrap;">{{ $lastMsg->created_at->diffForHumans(null, true) }}</span>
                    @endif
                </div>
                <p style="color:#64748b;font-size:0.78rem;margin:0.15rem 0 0;">
                    <i class="bi bi-book" style="font-size:0.7rem;"></i>
                    {{ $conversation->contract->subject ?? 'Contract' }}
                </p>
                @if($lastMsg)
                    <p style="color:#94a3b8;font-size:0.8rem;margin:0.25rem 0 0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        @if($lastMsg->sender_id === Auth::id())
                            <span style="color:#6366f1;font-weight:600;">You: </span>
                        @endif
                        {{ $lastMsg->hasAttachment() ? ($lastMsg->isImageAttachment() ? '📷 Photo' : '📄 File') : '' }}
                        {{ Str::limit($lastMsg->body, 60) }}
                    </p>
                @else
                    <p style="color:#cbd5e1;font-size:0.8rem;margin:0.25rem 0 0;font-style:italic;">No messages yet — say hello!</p>
                @endif
            </div>

            {{-- Unread Badge --}}
            @if($conversation->unread_count > 0)
                <div style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border-radius:999px;min-width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:800;padding:0 6px;flex-shrink:0;">
                    {{ $conversation->unread_count > 99 ? '99+' : $conversation->unread_count }}
                </div>
            @else
                <i class="bi bi-chevron-right" style="color:#cbd5e1;flex-shrink:0;"></i>
            @endif
        </a>
    @empty
        <div style="text-align:center;padding:4rem 0;">
            <div style="width:80px;height:80px;background:rgba(99,102,241,0.08);border-radius:24px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="bi bi-chat-dots" style="font-size:2.5rem;color:#6366f1;"></i>
            </div>
            <h3 style="color:#0f172a;font-weight:700;margin-bottom:0.5rem;">No conversations yet</h3>
            <p style="color:#94a3b8;font-size:0.88rem;max-width:400px;margin:0 auto;">
                Conversations are created automatically when you have an active contract.
                @if(strtolower(Auth::user()->role) === 'guardian')
                    <a href="{{ route('tutor_search_redirect') }}" style="color:#6366f1;font-weight:600;text-decoration:none;">Find a tutor →</a>
                @endif
            </p>
        </div>
    @endforelse

</div>
</div>

@endsection
