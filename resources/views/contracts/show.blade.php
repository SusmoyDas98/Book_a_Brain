@extends('layouts.app')

@section('content')
<div style="min-height:100vh; padding:3rem 0 5rem;">
<div class="container" style="max-width:900px;">

    @php $user = Auth::user(); $isTutor = $contract->tutor_id === $user->id; @endphp

    {{-- Header --}}
    <div class="mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:600;">Book-a-Brain</p>
            <h1 style="color:#0f172a;font-size:2rem;font-weight:700;margin-bottom:0.2rem;">Contract Details</h1>
            <p style="color:#64748b;margin:0;">
                {{ $isTutor ? 'Guardian: ' . $contract->guardian->name : 'Tutor: ' . $contract->tutor->name }}
            </p>
        </div>
        @if($contract->status === 'ACTIVE')
            <div class="d-flex gap-2 flex-wrap">
                {{-- Chat Button --}}
                <form action="{{ route('messages.start', $contract) }}" method="POST">
                    @csrf
                    <button type="submit" class="bab-btn-primary" style="padding:0.6rem 1.2rem;font-size:0.85rem;">
                        <i class="bi bi-chat-dots me-1"></i>Open Chat
                    </button>
                </form>

                {{-- End Contract --}}
                <form action="{{ route('contracts.end', $contract) }}" method="POST">
                    @csrf
                    <button type="submit" class="bab-btn-secondary" style="color:#ef4444;border-color:#fca5a5;"
                        onclick="return confirm('Are you sure you want to end this contract?')">
                        <i class="bi bi-x-circle me-1"></i>End Contract
                    </button>
                </form>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3" style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.25);color:#4f46e5;border-radius:16px;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- Contract Summary --}}
    <div class="bab-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="bab-section-title mb-0"><i class="bi bi-file-earmark-text me-2" style="color:#6366f1;"></i>Contract Summary</p>
            @if($contract->status === 'ACTIVE')
                <span style="background:rgba(34,197,94,0.1);color:#16a34a;border:1px solid rgba(34,197,94,0.3);border-radius:999px;font-size:0.75rem;font-weight:700;padding:3px 12px;">ACTIVE</span>
            @elseif($contract->status === 'PENDING')
                <span style="background:rgba(245,158,11,0.1);color:#d97706;border:1px solid rgba(245,158,11,0.3);border-radius:999px;font-size:0.75rem;font-weight:700;padding:3px 12px;">PENDING</span>
            @else
                <span style="background:rgba(100,116,139,0.1);color:#64748b;border:1px solid rgba(100,116,139,0.3);border-radius:999px;font-size:0.75rem;font-weight:700;padding:3px 12px;">ENDED</span>
            @endif
        </div>
        <div class="row g-3">
            <div class="col-md-3">
                <p class="bab-meta-label">Subject</p>
                <p class="bab-meta-value">{{ $contract->subject }}</p>
            </div>
            <div class="col-md-3">
                <p class="bab-meta-label">Schedule</p>
                <p class="bab-meta-value">{{ $contract->schedule }}</p>
            </div>
            <div class="col-md-3">
                <p class="bab-meta-label">Monthly Salary</p>
                <p class="bab-meta-value">৳{{ number_format($contract->salary) }}</p>
            </div>
            <div class="col-md-3">
                <p class="bab-meta-label">Start Date</p>
                <p class="bab-meta-value">{{ $contract->start_date->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Contract Notes --}}
    <div class="bab-card mb-4">
        <p class="bab-section-title"><i class="bi bi-chat-left-text me-2" style="color:#6366f1;"></i>Contract Notes</p>
        <form action="{{ route('contracts.notes', $contract) }}" method="POST">
            @csrf
            <div class="row g-3">
                @if($isTutor)
                    <div class="col-12">
                        <label class="bab-label">Your Notes</label>
                        <textarea name="tutor_notes" rows="3" class="bab-input" placeholder="Add notes about this contract...">{{ $contract->tutor_notes }}</textarea>
                    </div>
                    @if($contract->guardian_notes)
                        <div class="col-12">
                            <label class="bab-label">Guardian's Notes</label>
                            <p style="color:#1e293b;background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:0.75rem 1rem;font-size:0.9rem;">{{ $contract->guardian_notes }}</p>
                        </div>
                    @endif
                @else
                    <div class="col-12">
                        <label class="bab-label">Your Notes</label>
                        <textarea name="guardian_notes" rows="3" class="bab-input" placeholder="Add notes about this contract...">{{ $contract->guardian_notes }}</textarea>
                    </div>
                    @if($contract->tutor_notes)
                        <div class="col-12">
                            <label class="bab-label">Tutor's Notes</label>
                            <p style="color:#1e293b;background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:0.75rem 1rem;font-size:0.9rem;">{{ $contract->tutor_notes }}</p>
                        </div>
                    @endif
                @endif
            </div>
            <button type="submit" class="bab-btn-primary mt-3" style="padding:0.6rem 1.4rem;font-size:0.85rem;">
                Save Notes
            </button>
        </form>
    </div>

    {{-- SESSION LOGGING (Month 2+ and tutor only) --}}
    @if($contract->status === 'ACTIVE' && $contract->isPastFirstMonth() && $isTutor)
        <div class="bab-card mb-4">
            <p class="bab-section-title"><i class="bi bi-calendar-check me-2" style="color:#6366f1;"></i>Log This Week's Sessions</p>
            <form action="{{ route('contracts.log', $contract) }}" method="POST">
                @csrf
                <input type="hidden" name="week_start" value="{{ $currentWeekStart }}">

                <p style="color:#64748b;font-size:0.82rem;margin-bottom:1rem;">
                    Week of {{ \Carbon\Carbon::parse($currentWeekStart)->format('d M Y') }}
                </p>

                <div class="d-flex flex-wrap gap-3 mb-3">
                    @foreach(['mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun'] as $key => $label)
                        <label style="display:flex;align-items:center;gap:8px;background:white;border:2px solid {{ optional($currentLog)?->$key ? '#6366f1' : '#e2e8f0' }};border-radius:12px;padding:8px 16px;cursor:pointer;font-weight:600;color:{{ optional($currentLog)?->$key ? '#6366f1' : '#64748b' }};font-size:0.85rem;transition:all 0.2s;">
                            <input type="checkbox" name="{{ $key }}" value="1" style="accent-color:#6366f1;"
                                {{ optional($currentLog)?->$key ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                    @endforeach
                </div>

                <label class="bab-label">Session Note (optional)</label>
                <textarea name="tutor_note" rows="2" class="bab-input mb-3"
                    placeholder="e.g. Covered chapter 3, student improving...">{{ optional($currentLog)->tutor_note }}</textarea>

                <button type="submit" class="bab-btn-primary" style="padding:0.6rem 1.4rem;font-size:0.85rem;">
                    <i class="bi bi-check2 me-1"></i>Save Week Log
                </button>
            </form>
        </div>
    @elseif($contract->status === 'ACTIVE' && !$contract->isPastFirstMonth())
        <div class="bab-card mb-4" style="border-left:4px solid #6366f1;">
            <p style="color:#6366f1;font-weight:600;margin:0;">
                <i class="bi bi-info-circle me-2"></i>
                Session logging becomes available after the first month
                ({{ $contract->start_date->addMonth()->format('d M Y') }}).
            </p>
        </div>
    @endif

    {{-- SESSION LOG HISTORY --}}
    @if($sessionLogs->count())
        <div class="bab-card">
            <p class="bab-section-title"><i class="bi bi-clock-history me-2" style="color:#6366f1;"></i>Session History</p>
            @foreach($sessionLogs as $log)
                <div style="border:1px solid #e2e8f0;border-radius:16px;padding:1rem;margin-bottom:0.75rem;">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                        <p style="font-weight:600;color:#0f172a;margin:0;font-size:0.9rem;">
                            Week of {{ $log->week_start->format('d M Y') }}
                        </p>
                        <span style="background:rgba(99,102,241,0.1);color:#6366f1;border-radius:999px;font-size:0.75rem;font-weight:700;padding:2px 10px;">
                            {{ $log->sessionCount() }} session{{ $log->sessionCount() !== 1 ? 's' : '' }}
                        </span>
                    </div>

                    {{-- Day chips --}}
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        @foreach(['mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun'] as $key => $label)
                            <span style="background:{{ $log->$key ? 'rgba(99,102,241,0.12)' : '#f1f5f9' }};color:{{ $log->$key ? '#6366f1' : '#94a3b8' }};border-radius:8px;font-size:0.75rem;font-weight:600;padding:3px 10px;">
                                {{ $label }}
                            </span>
                        @endforeach
                    </div>

                    @if($log->tutor_note)
                        <p style="font-size:0.8rem;color:#64748b;margin-bottom:0.4rem;">
                            <span style="font-weight:600;color:#0f172a;">Tutor:</span> {{ $log->tutor_note }}
                        </p>
                    @endif

                    {{-- Guardian note --}}
                    @if(!$isTutor && $contract->status === 'ACTIVE')
                        <form action="{{ route('contracts.guardian_note', $log) }}" method="POST" class="mt-2">
                            @csrf
                            <div class="d-flex gap-2">
                                <input type="text" name="guardian_note" class="bab-input"
                                    style="font-size:0.82rem;padding:0.45rem 0.8rem;"
                                    placeholder="Add your note..."
                                    value="{{ $log->guardian_note }}">
                                <button type="submit" class="bab-btn-primary" style="padding:0.45rem 1rem;font-size:0.82rem;white-space:nowrap;">Save</button>
                            </div>
                        </form>
                    @elseif($log->guardian_note)
                        <p style="font-size:0.8rem;color:#64748b;margin:0.4rem 0 0;">
                            <span style="font-weight:600;color:#0f172a;">Guardian:</span> {{ $log->guardian_note }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- REVIEWS SECTION --}}
    @php $reviews = $contract->reviews()->with('guardian')->latest()->get(); @endphp

    <div class="bab-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="bab-section-title mb-0"><i class="bi bi-star me-2" style="color:#f59e0b;"></i>Reviews</p>
            @if(!$isTutor && in_array($contract->status, ['ACTIVE', 'ENDED']))
                @php $hasReviewed = $reviews->where('guardian_id', Auth::id())->first(); @endphp
                <a href="{{ route('reviews.create', $contract) }}" class="bab-btn-primary" style="padding:0.5rem 1rem;font-size:0.82rem;">
                    <i class="bi bi-pencil me-1"></i>{{ $hasReviewed ? 'Edit Review' : 'Leave a Review' }}
                </a>
            @endif
        </div>

        @forelse($reviews as $review)
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:0.85rem 1rem;margin-bottom:0.6rem;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div style="color:#f59e0b;font-size:0.9rem;margin-bottom:0.25rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                            <span style="color:#0f172a;font-weight:700;font-size:0.82rem;margin-left:6px;">{{ $review->rating }}/5</span>
                        </div>
                        @if($review->comment)
                            <p style="color:#64748b;font-size:0.85rem;margin:0.3rem 0 0;line-height:1.5;">{{ $review->comment }}</p>
                        @endif
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <p style="color:#94a3b8;font-size:0.72rem;margin:0;">{{ $review->guardian->name }}</p>
                        <p style="color:#cbd5e1;font-size:0.68rem;margin:0;">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:1.5rem 0;">
                <i class="bi bi-chat-square-text" style="font-size:1.5rem;color:#cbd5e1;"></i>
                <p style="color:#94a3b8;font-size:0.83rem;margin:0.5rem 0 0;">No reviews yet.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        <a href="{{ $isTutor ? route('contracts.tutor') : route('contracts.guardian') }}" class="bab-btn-secondary">
            ← Back to Contracts
        </a>
    </div>

</div>
</div>
@endsection