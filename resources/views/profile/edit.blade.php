@extends('layouts.app')

@section('content')

<div style="min-height:100vh;padding:3rem 0 5rem;">
    <div class="container" style="max-width:860px;">

        {{-- Page Header --}}
        <div class="mb-4">
            <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:600;">Book-a-Brain</p>
            <h1 style="color:#0f172a;font-size:2rem;font-weight:700;margin-top:0.3rem;margin-bottom:0.4rem;">Refine Your Profile</h1>
            <p style="color:#64748b;">Update your details to maintain a polished and verified presence.</p>
        </div>

        {{-- COMPLETION BAR --}}
        <div class="bab-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.25em;color:#6366f1;font-weight:600;">
                    Profile Completion
                </span>
                <span style="font-weight:700;font-size:1rem;color:{{ $completionPercentage == 100 ? '#6366f1' : '#e2e8f0' }};">
                    @if($completionPercentage == 100)
                        <i class="bi bi-patch-check-fill me-1"></i>100% Complete
                    @else
                        {{ $completionPercentage }}%
                    @endif
                </span>
            </div>
            <div style="height:8px;background:#e2e8f0;border-radius:999px;overflow:hidden;">
                <div style="height:100%;width:{{ $completionPercentage }}%;background:linear-gradient(90deg,#06b6d4,#6366f1);border-radius:999px;"></div>
            </div>
        </div>

        {{-- FORM --}}
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- ── CORE INFORMATION ── --}}
            <div class="bab-card">
                <p class="bab-section-title"><i class="bi bi-person-circle me-2" style="color:#6366f1;"></i>Core Information</p>

                {{-- Picture row --}}
                <div class="d-flex align-items-center gap-4 mb-4">
                    @php
                        $pic = null;
                        if (strtolower($user->role) === 'tutor' && optional($tutorProfile)->profile_picture)
                            $pic = asset('storage/' . $tutorProfile->profile_picture);
                        elseif (strtolower($user->role) === 'guardian' && optional($guardian)->profile_picture)
                            $pic = asset('storage/' . $guardian->profile_picture);
                    @endphp

                    @if($pic)
                        <img src="{{ $pic }}" alt="Current picture"
                             style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #6366f1;flex-shrink:0;">
                    @else
                        <img src="{{ asset('images/default_avatar.png') }}" alt="Default"
                             style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #334155;flex-shrink:0;opacity:0.65;">
                    @endif

                    <div style="flex:1;">
                        <label class="bab-label">Profile Picture <span style="color:#94a3b8;">(JPG/PNG, max 2MB)</span></label>
                        <input type="file" name="profile_picture" accept=".jpg,.jpeg,.png" class="bab-file-input">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="bab-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="bab-input">
                    </div>
                    <div class="col-md-6">
                        <label class="bab-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="bab-input">
                    </div>
                    <div class="col-md-6">
                        <label class="bab-label">Gender</label>
                        <select name="gender" class="bab-input">
                            <option value="">— Select —</option>
                            @php
                                $currentGender = strtolower($user->role) === 'tutor'
                                    ? optional($tutorProfile)->gender
                                    : optional($guardian)->gender;
                            @endphp
                            @foreach(['Male','Female','Other','Prefer not to say'] as $g)
                                <option value="{{ $g }}" {{ old('gender', $currentGender) === $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(strtolower($user->role) === 'tutor')
                    <div class="col-md-6">
                        <label class="bab-label">Phone</label>
                        <input type="text" name="contact_no" value="{{ old('contact_no', optional($tutorProfile)->contact_no) }}" class="bab-input">
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── TUTOR SECTIONS ── --}}
            @if(strtolower($user->role) === 'tutor')

                <div class="bab-card">
                    <p class="bab-section-title"><i class="bi bi-journal-text me-2" style="color:#6366f1;"></i>Teaching Details</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="bab-label">Teaching Method</label>
                            <input type="text" name="teaching_method" value="{{ old('teaching_method', optional($tutorProfile)->teaching_method) }}" class="bab-input">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Availability</label>
                            <input type="text" name="availability" value="{{ old('availability', optional($tutorProfile)->availability) }}" class="bab-input">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Preferred Mediums</label>
                            <input type="text" name="preferred_mediums" value="{{ old('preferred_mediums', optional($tutorProfile)->preferred_mediums) }}" class="bab-input">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Preferred Subjects</label>
                            <input type="text" name="preferred_subjects" value="{{ old('preferred_subjects', optional($tutorProfile)->preferred_subjects) }}" class="bab-input">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Expected Salary (৳)</label>
                            <input type="text" name="expected_salary" value="{{ old('expected_salary', optional($tutorProfile)->expected_salary) }}" class="bab-input">
                        </div>
                    </div>
                </div>

                <div class="bab-card">
                    <p class="bab-section-title"><i class="bi bi-mortarboard me-2" style="color:#6366f1;"></i>Background</p>
                    <div class="mb-3">
                        <label class="bab-label">Educational Institutions</label>
                        <textarea name="educational_institutions" rows="3" class="bab-input">{{ old('educational_institutions', optional($tutorProfile)->educational_institutions) }}</textarea>
                    </div>
                    <div>
                        <label class="bab-label">Work Experience</label>
                        <textarea name="work_experience" rows="3" class="bab-input">{{ old('work_experience', optional($tutorProfile)->work_experience) }}</textarea>
                    </div>
                </div>

                <div class="bab-card">
                    <p class="bab-section-title"><i class="bi bi-shield-check me-2" style="color:#6366f1;"></i>Verification &amp; Documents</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="bab-label">Upload CV <span style="color:#94a3b8;">(PDF only)</span></label>
                            <input type="file" name="cv" accept=".pdf" class="bab-file-input">
                            @if(optional($tutorProfile)->cv)
                                <a href="{{ asset('storage/' . $tutorProfile->cv) }}" target="_blank"
                                   style="color:#6366f1;font-size:0.8rem;margin-top:0.4rem;display:inline-block;">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>Current CV ↗
                                </a>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Upload NID</label>
                            <input type="file" name="nid_document" accept=".pdf,.jpg,.jpeg,.png" class="bab-file-input">
                        </div>
                        <div class="col-12">
                            <label class="bab-label">Upload Occupation Verification Card</label>
                            <input type="file" name="occupation_document" accept=".pdf,.jpg,.jpeg,.png" class="bab-file-input">
                        </div>
                    </div>
                </div>

            @endif

            {{-- ── GUARDIAN SECTIONS ── --}}
            @if(strtolower($user->role) === 'guardian')

                <div class="bab-card">
                    <p class="bab-section-title"><i class="bi bi-house me-2" style="color:#6366f1;"></i>Guardian Details</p>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="bab-label">Address</label>
                            <input type="text" name="address" value="{{ old('address', optional($guardian)->address) }}" class="bab-input">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Latitude</label>
                            <input type="text" name="latitude" value="{{ old('latitude', optional($guardian)->latitude) }}" class="bab-input">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude', optional($guardian)->longitude) }}" class="bab-input">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Number of Children</label>
                            <input type="number" name="number_of_children" value="{{ old('number_of_children', optional($guardian)->number_of_children) }}" class="bab-input">
                        </div>
                        <div class="col-md-6">
                            <label class="bab-label">Preferred Subjects</label>
                            <input type="text" name="preferred_subjects" value="{{ old('preferred_subjects', optional($guardian)->preferred_subjects) }}" class="bab-input">
                        </div>
                    </div>
                </div>

            @endif
            {{-- SUBMIT --}}
            <div class="d-flex flex-wrap gap-3 align-items-center mt-2">

                {{-- Save button --}}
                <button type="submit" class="bab-btn-primary">
                    <i class="bi bi-check2 me-1"></i>Save Changes
                </button>

                {{-- Skip → goes to profile show page --}}
                <a href="{{ route('profile.show') }}" class="bab-btn-secondary">
                    Skip for now
                </a>

                {{-- Confirm → only appears when profile is 100% complete --}}
                @if($completionPercentage >= 100)
                    <form action="{{ route('profile.confirm') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="bab-btn-primary"
                            style="background:linear-gradient(90deg,#6366f1,#4f46e5);box-shadow:0 6px 20px rgba(99,102,241,0.35);">
                            <i class="bi bi-patch-check-fill me-2"></i>Confirm & Continue
                        </button>
                    </form>
                @endif

            </div>

            {{-- Error message if user tried to confirm before 100% --}}
            @if(session('error'))
                <div class="mt-3 px-4 py-3"
                    style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444;border-radius:16px;">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

                    </form>
                </div>
            </div>

            @endsection