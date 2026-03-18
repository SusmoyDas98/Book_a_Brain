@extends('layouts.app')

@section('content')

{{-- TOP BANNER --}}
<div style="background: rgba(255,255,255,0.6); border-bottom: 1px solid #1e293b; padding: 2.5rem 0 2rem;">
    <div class="container">

        @if(session('success'))
        <div class="mb-4 px-4 py-3" style="background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.25); color: #86efac; border-radius: 16px;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
        @endif

        {{-- COMPLETION BAR --}}
        <div class="bab-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.25em; color: #6366f1; font-weight: 600;">
                    Profile Completion
                </span>
                <span style="font-weight: 700; font-size: 1rem; color: {{ $completionPercentage == 100 ? '#6366f1' : '#1e293b' }};">
                    @if($completionPercentage == 100)
                        <i class="bi bi-patch-check-fill me-1"></i>100% Complete
                    @else
                        {{ $completionPercentage }}%
                    @endif
                </span>
            </div>
            <div style="height: 8px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                <div style="height: 100%; width: {{ $completionPercentage }}%; background: linear-gradient(90deg,#06b6d4,#6366f1); border-radius: 999px; transition: width 0.6s ease;"></div>
            </div>
            @if($completionPercentage < 100)
                <p class="mt-2 mb-0" style="color: #64748b; font-size: 0.8rem;">
                    Fill in all sections to reach 100% &mdash;
                    <a href="{{ route('profile.edit') }}" style="color: #6366f1; text-decoration: none;">complete your profile →</a>
                </p>
            @endif
        </div>

        <p style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3em; color: #6366f1; font-weight: 600;">Book-a-Brain</p>
        <h1 style="color: #0f172a; font-size: 2rem; font-weight: 700; margin-top: 0.3rem; margin-bottom: 0.4rem;">Your Academic Profile</h1>
        <p style="color: #64748b; max-width: 520px; margin: 0;">Manage your identity, academic depth, and professional presence.</p>
    </div>
</div>

{{-- BODY --}}
<div style="min-height: 100vh; padding: 2.5rem 0 5rem;">
    <div class="container">
        <div class="row g-4">

            {{-- ── LEFT COLUMN: Identity card ── --}}
            <div class="col-lg-4">
                <div class="bab-card h-100 text-center">

                    {{-- Profile Picture --}}
                    @php
                        $pic = null;
                        if (strtolower($user->role) === 'tutor'    && optional($tutorProfile)->profile_picture)
                            $pic = asset('storage/' . $tutorProfile->profile_picture);
                        elseif (strtolower($user->role) === 'guardian' && optional($guardian)->profile_picture)
                            $pic = asset('storage/' . $guardian->profile_picture);
                    @endphp

                    @if($pic)
                        <img src="{{ $pic }}" alt="Profile Picture"
                             style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #6366f1;margin-bottom:1rem;">
                    @else
                        <img src="{{ asset('images/default_avatar.png') }}" alt="Default Avatar"
                             style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #334155;margin-bottom:1rem;opacity:0.7;">
                    @endif

                    <h5 style="color:#0f172a;font-weight:700;margin-bottom:0.2rem;">{{ $user->name }}</h5>
                    <span style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.2em;color:#6366f1;font-weight:600;">
                        {{ $user->role }}
                    </span>

                    <hr style="border-color:#e2e8f0;margin:1.25rem 0;">

                    {{-- Core Info --}}
                    <div class="text-start">
                        <p class="bab-meta-label"><i class="bi bi-envelope me-1"></i>Email</p>
                        <p class="bab-meta-value mb-3" style="word-break:break-all;">{{ $user->email }}</p>

                        @if(strtolower($user->role) === 'tutor')
                            <p class="bab-meta-label"><i class="bi bi-telephone me-1"></i>Phone</p>
                            <p class="bab-meta-value mb-3">{{ optional($tutorProfile)->contact_no ?: '—' }}</p>
                        @endif

                        <p class="bab-meta-label"><i class="bi bi-person me-1"></i>Gender</p>
                        <p class="bab-meta-value mb-0">
                            @if(strtolower($user->role) === 'tutor')
                                {{ optional($tutorProfile)->gender ?: '—' }}
                            @else
                                {{ optional($guardian)->gender ?: '—' }}
                            @endif
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('profile.edit') }}" class="bab-btn-primary w-100 text-center">
                            <i class="bi bi-pencil me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT COLUMN: Details ── --}}
            <div class="col-lg-8">

                @if(strtolower($user->role) === 'tutor')

                    {{-- Teaching Details --}}
                    <div class="bab-card">
                        <p class="bab-section-title"><i class="bi bi-journal-text me-2" style="color:#6366f1;"></i>Teaching Details</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="bab-meta-label">Teaching Method</p>
                                <p class="bab-meta-value">{{ optional($tutorProfile)->teaching_method ?: '—' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="bab-meta-label">Availability</p>
                                <p class="bab-meta-value">{{ optional($tutorProfile)->availability ?: '—' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="bab-meta-label">Preferred Mediums</p>
                                <p class="bab-meta-value">{{ optional($tutorProfile)->preferred_mediums ?: '—' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="bab-meta-label">Preferred Subjects</p>
                                <p class="bab-meta-value">{{ optional($tutorProfile)->preferred_subjects ?: '—' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="bab-meta-label">Expected Salary</p>
                                <p class="bab-meta-value">
                                    {{ optional($tutorProfile)->expected_salary ? '৳ ' . number_format($tutorProfile->expected_salary) : '—' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="bab-meta-label">CV</p>
                                @if(optional($tutorProfile)->cv)
                                    <a href="{{ asset('storage/' . $tutorProfile->cv) }}" target="_blank" style="color:#6366f1;font-size:0.9rem;">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>View CV ↗
                                    </a>
                                @else
                                    <p class="bab-meta-value">—</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Background --}}
                    <div class="bab-card">
                        <p class="bab-section-title"><i class="bi bi-mortarboard me-2" style="color:#6366f1;"></i>Background</p>
                        <p class="bab-meta-label">Educational Institutions</p>
                        <p class="bab-meta-value mb-3">{{ optional($tutorProfile)->educational_institutions ?: '—' }}</p>
                        <p class="bab-meta-label">Work Experience</p>
                        <p class="bab-meta-value mb-0">{{ optional($tutorProfile)->work_experience ?: '—' }}</p>
                    </div>

                    {{-- Verification Documents --}}
                    <div class="bab-card">
                        <p class="bab-section-title"><i class="bi bi-shield-check me-2" style="color:#6366f1;"></i>Verification Documents</p>
                        @php
                            $nidDoc        = $verificationDocuments->firstWhere('doc_type', 'NID');
                            $occupationDoc = $verificationDocuments->firstWhere('doc_type', 'OCCUPATION_CARD');
                        @endphp
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div style="background:#f1f0ff; border:1px solid #e0e7ff; border-radius:16px; padding:1rem;">
                                    <p style="color:#f1f5f9; font-weight:600; margin-bottom:0.6rem; background:#6366f1; display:inline-flex; align-items:center; gap:0.4rem; padding:0.25rem 0.75rem; border-radius:8px; font-size:0.85rem;">
                                        <i class="bi bi-card-text"></i>NID Document
                                    </p>
                                    <p style="font-size:0.82rem; color:#64748b; margin-bottom:0.5rem; margin-top:0.25rem;">
                                        Status: <span style="color:{{ $nidDoc ? '#6366f1' : '#94a3b8' }}; font-weight:600;">{{ $nidDoc->status ?? 'Not uploaded' }}</span>
                                    </p>
                                    @if($nidDoc)
                                        <a href="{{ asset('storage/' . $nidDoc->file_path) }}" target="_blank" style="color:#6366f1; font-size:0.85rem; font-weight:500;">
                                            View File ↗
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background:#f1f0ff; border:1px solid #e0e7ff; border-radius:16px; padding:1rem;">
                                    <p style="color:#f1f5f9; font-weight:600; margin-bottom:0.6rem; background:#6366f1; display:inline-flex; align-items:center; gap:0.4rem; padding:0.25rem 0.75rem; border-radius:8px; font-size:0.85rem;">
                                        <i class="bi bi-person-badge"></i>Job/Student ID
                                    </p>
                                    <p style="font-size:0.82rem; color:#64748b; margin-bottom:0.5rem; margin-top:0.25rem;">
                                        Status: <span style="color:{{ $occupationDoc ? '#6366f1' : '#94a3b8' }}; font-weight:600;">{{ $occupationDoc->status ?? 'Not uploaded' }}</span>
                                    </p>
                                    @if($occupationDoc)
                                        <a href="{{ asset('storage/' . $occupationDoc->file_path) }}" target="_blank" style="color:#6366f1; font-size:0.85rem; font-weight:500;">
                                            View File ↗
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                @endif

                @if(strtolower($user->role) === 'guardian')

                    <div class="bab-card">
                        <p class="bab-section-title"><i class="bi bi-house me-2" style="color:#6366f1;"></i>Guardian Details</p>
                        <div class="row g-3">
                            <div class="col-12">
                                <p class="bab-meta-label">Address</p>
                                <p class="bab-meta-value">{{ optional($guardian)->address ?: '—' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="bab-meta-label">Contact No</p>
                                <p class="bab-meta-value">{{ optional($guardian)->contact_no ?: '—' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="bab-meta-label">Location</p>
                                <p class="bab-meta-value">
                                    @if(optional($guardian)->location)
                                        {{ $guardian->location['lat'] ?? '—' }}, {{ $guardian->location['lng'] ?? '—' }}
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="bab-meta-label">NID</p>
                                @if(optional($guardian)->nid_card)
                                    <a href="{{ asset('storage/' . $guardian->nid_card) }}" target="_blank" style="color:#6366f1;font-size:0.9rem;">
                                        <i class="bi bi-file-earmark me-1"></i>View NID ↗
                                    </a>
                                @else
                                    <p class="bab-meta-value">—</p>
                                @endif
                            </div>
                        </div>
                    </div>

                @endif

            </div>
        </div>
    </div>
</div>

@endsection