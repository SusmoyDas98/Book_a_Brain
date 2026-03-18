@extends('layouts.app')

@section('content')
<div style="min-height:100vh; padding:3rem 0 5rem;">
<div class="container" style="max-width:680px;">

    <div class="mb-4">
        <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:600;">Book-a-Brain</p>
        <h1 style="color:#0f172a;font-size:2rem;font-weight:700;margin-bottom:0.3rem;">Hire Tutor</h1>
        <p style="color:#64748b;">Fill in the details to send a hire request to <strong>{{ $tutor->name }}</strong>.</p>
    </div>

    @if($existing)
        <div class="bab-card mb-4" style="border-left:4px solid #f59e0b;">
            <p style="color:#d97706;font-weight:600;margin:0;">
                <i class="bi bi-exclamation-triangle me-2"></i>
                You already have a {{ strtolower($existing->status) }} contract with this tutor.
                <a href="{{ route('contracts.show', $existing) }}" style="color:#6366f1;">View it →</a>
            </p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 px-4 py-3" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444;border-radius:16px;">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Tutor Summary Card --}}
    <div class="bab-card mb-4 d-flex align-items-center gap-3">
        @php
            $pic = optional($tutorProfile)->profile_picture
                ? asset('storage/' . $tutorProfile->profile_picture)
                : asset('images/default_avatar.png');
        @endphp
        <img src="{{ $pic }}" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;flex-shrink:0;">
        <div>
            <p style="font-weight:700;color:#0f172a;margin:0;">{{ $tutor->name }}</p>
            <p style="color:#64748b;font-size:0.82rem;margin:0;">
                {{ is_array(optional($tutorProfile)->preferred_subjects) ? implode(', ', $tutorProfile->preferred_subjects) : (optional($tutorProfile)->preferred_subjects ?? '') }}
                @if(optional($tutorProfile)->expected_salary)
                    @php
                        $salary = is_array($tutorProfile->expected_salary)
                            ? implode(', ', $tutorProfile->expected_salary)
                            : $tutorProfile->expected_salary;
                    @endphp
                    &nbsp;·&nbsp; ৳{{ number_format($salary) }}/month
                @endif
            </p>
        </div>
    </div>

    {{-- Hire Form --}}
    <form action="{{ route('contracts.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tutor_id" value="{{ $tutor->id }}">

        <div class="bab-card">
            <p class="bab-section-title"><i class="bi bi-file-earmark-text me-2" style="color:#6366f1;"></i>Contract Details</p>

            <div class="row g-3">
                <div class="col-12">
                    <label class="bab-label">Subject(s) to be taught</label>
                    <input type="text" name="subject" class="bab-input"
                        value="{{ old('subject', is_array(optional($tutorProfile)->preferred_subjects) ? implode(', ', $tutorProfile->preferred_subjects) : optional($tutorProfile)->preferred_subjects) }}"
                        placeholder="e.g. Math, Physics" required>
                    @error('subject') <span style="color:#ef4444;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-12">
                    <label class="bab-label">Schedule</label>
                    <input type="text" name="schedule" class="bab-input"
                        value="{{ old('schedule') }}"
                        placeholder="e.g. Mon, Wed, Fri — 5:00 PM" required>
                    @error('schedule') <span style="color:#ef4444;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6">
                    <label class="bab-label">Monthly Salary (৳)</label>
                    <input type="number" name="salary" class="bab-input"
                        value="{{ old('salary', is_array(optional($tutorProfile)->expected_salary) ? '' : optional($tutorProfile)->expected_salary) }}"
                        placeholder="e.g. 5000" min="0" required>
                    @error('salary') <span style="color:#ef4444;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6">
                    <label class="bab-label">Start Date</label>
                    <input type="date" name="start_date" class="bab-input"
                        value="{{ old('start_date', now()->addDay()->format('Y-m-d')) }}"
                        min="{{ now()->format('Y-m-d') }}" required>
                    @error('start_date') <span style="color:#ef4444;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="d-flex gap-3 mt-2">
            <button type="submit" class="bab-btn-primary">
                <i class="bi bi-send me-2"></i>Send Hire Request
            </button>
            <a href="{{ route('tutor_search_redirect') }}" class="bab-btn-secondary">Cancel</a>
        </div>
    </form>

</div>
</div>
@endsection