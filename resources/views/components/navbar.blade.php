<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container navbar-container">

        {{-- LOGO → Landing Page --}}
        <a href="{{ route('landing_page') }}" class="navbar-brand-custom">
            <img src="{{ asset('images/book-a-brain_logo.png') }}" alt="Logo" style="height:40px;width:auto;display:block;">
        </a>

        {{-- RIGHT SIDE --}}
        <div class="navbar-actions">

            @auth
                {{-- Role-based nav links --}}
                @if(strtolower(Auth::user()->role) === 'guardian')
                    <a href="{{ route('tutor_search_redirect') }}" style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border-radius:12px;padding:7px 16px;border:none;font-size:0.85rem;" class="btn btn-tutor-search">Find Tutors</a>
                     <a href="{{ route('job_posts.index') }}" style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border-radius:12px;padding:7px 16px;border:none;font-size:0.85rem;" class="btn btn-tutor-search">Post Jobs</a>
                    <a href="{{ route('contracts.guardian') }}" class="btn"
                    style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border-radius:12px;padding:7px 16px;border:none;font-size:0.85rem;">
                    My Contracts
                </a>
                    <a href="{{ route('dashboard') }}" class="btn"
                    style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border-radius:12px;padding:7px 16px;border:none;font-size:0.85rem;">
                    Dashboard
                </a>
                <a href="{{ route('complaints.my') }}" class="btn"
                    style="background:#fee2e2;color:#ef4444;font-weight:700;border-radius:12px;padding:7px 16px;border:none;font-size:0.85rem;">
                    My Complaints
                </a>
                @elseif(strtolower(Auth::user()->role) === 'tutor')
                    <a href="{{ route('contracts.tutor') }}" class="btn"
                    style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border-radius:12px;padding:7px 16px;border:none;font-size:0.85rem;">
                    My Contracts
                </a>
                <a href="{{ route('jobs.browse') }}" class="btn" id="tutorDashboardLink"
                    style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border-radius:12px;padding:7px 16px;border:none;font-size:0.85rem;">
                    Browse Jobs
                </a>                
                <a href="{{ route('dashboard') }}" class="btn" id="tutorDashboardLink"
                    style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border-radius:12px;padding:7px 16px;border:none;font-size:0.85rem;">
                    Dashboard
                </a>
                <a href="{{ route('complaints.my') }}" class="btn"
                    style="background:#fee2e2;color:#ef4444;font-weight:700;border-radius:12px;padding:7px 16px;border:none;font-size:0.85rem;">
                    My Complaints
                </a>
                @elseif(strtolower(Auth::user()->role) === 'admin')
                    <a href="{{ route('dashboard') }}" class="btn btn-tutor-search">Admin Dashboard</a>
                    
                @endif

                {{-- Messages icon with unread badge --}}
                @if(in_array(strtolower(Auth::user()->role), ['guardian', 'tutor']))
                    @php
                        $navUnreadCount = \App\Models\Message::whereHas('conversation', function($q) {
                            $q->where('guardian_id', Auth::id())->orWhere('tutor_id', Auth::id());
                        })->where('sender_id', '!=', Auth::id())->whereNull('read_at')->count();
                    @endphp
                    <a href="{{ route('messages.index') }}"
                       style="position:relative;background:rgba(99,102,241,0.08);color:#6366f1;border-radius:12px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;text-decoration:none;transition:0.2s;"
                       onmouseover="this.style.background='rgba(99,102,241,0.15)'"
                       onmouseout="this.style.background='rgba(99,102,241,0.08)'"
                       title="Messages">
                        <i class="bi bi-chat-dots-fill" style="font-size:1.1rem;"></i>
                        @if($navUnreadCount > 0)
                            <span style="position:absolute;top:-4px;right:-4px;background:#ef4444;color:white;border-radius:999px;min-width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:800;padding:0 4px;border:2px solid white;">
                                {{ $navUnreadCount > 99 ? '99+' : $navUnreadCount }}
                            </span>
                        @endif
                    </a>
                @endif

                <button class="btn btn-upgrade">UPGRADE</button>

                {{-- Profile dropdown --}}
                <div style="position:relative;" id="profileDropdownWrapper">
                    @php
                        $navPic = null;
                        $navUser = Auth::user();
                        if (strtolower($navUser->role) === 'tutor') {
                            $navProfile = \App\Models\TutorProfile::where('tutor_id', $navUser->id)->first();
                            $navPic = optional($navProfile)->profile_picture
                                ? asset('storage/' . $navProfile->profile_picture) : null;
                        } elseif (strtolower($navUser->role) === 'guardian') {
                            $navGuardian = \App\Models\Guardian::where('guardian_id', $navUser->id)->first();
                            $navPic = optional($navGuardian)->profile_picture
                                ? asset('storage/' . $navGuardian->profile_picture) : null;
                        }
                    @endphp

                    <a href="#" onclick="toggleProfileDropdown(event)"
                        style="display:flex;align-items:center;gap:8px;background:linear-gradient(135deg,#6366f1,#4f46e5);border-radius:999px;padding:4px 12px 4px 4px;text-decoration:none;">
                            <img src="{{ $navPic ?? asset('images/default_avatar.png') }}" alt="Profile"
                                style="height:32px;width:32px;border-radius:50%;object-fit:cover;border:2px solid white;">
                            <span style="color:white;font-weight:700;font-size:0.82rem;">Profile</span>
                        </a>

                    {{-- Dropdown --}}
                    <div id="profileDropdown" style="display:none;position:absolute;right:0;top:52px;background:white;border:2px solid #e2e8f0;border-radius:16px;padding:0.5rem;min-width:180px;box-shadow:0 8px 30px rgba(0,0,0,0.1);z-index:9999;">
                        <div style="padding:0.6rem 0.75rem;border-bottom:1px solid #f1f5f9;margin-bottom:0.25rem;">
                            <p style="font-weight:700;color:#0f172a;font-size:0.85rem;margin:0;">{{ Auth::user()->name }}</p>
                            <p style="color:#94a3b8;font-size:0.75rem;margin:0;text-transform:capitalize;">{{ Auth::user()->role }}</p>
                        </div>
                        <a href="{{ route('profile.show') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.55rem 0.75rem;color:#0f172a;text-decoration:none;border-radius:10px;font-size:0.85rem;font-weight:600;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <i class="bi bi-person-circle" style="color:#6366f1;"></i> My Profile
                        </a>
                        <a href="{{ route('profile.edit') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.55rem 0.75rem;color:#0f172a;text-decoration:none;border-radius:10px;font-size:0.85rem;font-weight:600;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <i class="bi bi-pencil" style="color:#6366f1;"></i> Edit Profile
                        </a>
                        <a href="{{ route('dashboard') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.55rem 0.75rem;color:#0f172a;text-decoration:none;border-radius:10px;font-size:0.85rem;font-weight:600;transition:0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <i class="bi bi-speedometer2" style="color:#6366f1;"></i> Dashboard
                        </a>
                        <div style="border-top:1px solid #f1f5f9;margin-top:0.25rem;padding-top:0.25rem;">
                            <form action="{{ route('auth.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="action_type" value="logout">
                                <button type="submit" style="display:flex;align-items:center;gap:0.6rem;padding:0.55rem 0.75rem;color:#ef4444;background:none;border:none;border-radius:10px;font-size:0.85rem;font-weight:600;width:100%;cursor:pointer;transition:0.15s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            @else
                <a href="{{ route('login_or_signup_page_redirect') }}" class="btn btn-tutor-search">Login</a>
                <a href="{{ route('login_or_signup_page_redirect') }}" class="btn btn-upgrade">UPGRADE</a>
            @endauth

        </div>
    </div>
</nav>

{{-- Tutor completion popup --}}
@auth
@if(strtolower(Auth::user()->role) === 'tutor')
@php
    $navTutorProfile = \App\Models\TutorProfile::where('tutor_id', Auth::id())->first();
    $navVerDocs      = \App\Models\VerificationDocument::where('tutor_id', Auth::id())->get();
    $navFields = [
        Auth::user()->name, Auth::user()->email,
        optional($navTutorProfile)->contact_no,
        optional($navTutorProfile)->gender,
        optional($navTutorProfile)->profile_picture,
        optional($navTutorProfile)->cv,
        optional($navTutorProfile)->educational_institutions,
        optional($navTutorProfile)->work_experience,
        optional($navTutorProfile)->teaching_method,
        optional($navTutorProfile)->availability,
        optional($navTutorProfile)->preferred_mediums,
        optional($navTutorProfile)->preferred_subjects,
        optional($navTutorProfile)->expected_salary,
        $navVerDocs->firstWhere('doc_type','NID')?->file_path,
        $navVerDocs->firstWhere('doc_type','OCCUPATION_CARD')?->file_path,
    ];
    $navCompletion = round((collect($navFields)->filter(fn($v) => !is_null($v) && $v !== '')->count() / count($navFields)) * 100);
@endphp
<script>
    const tutorCompletion = {{ $navCompletion }};

    function toggleProfileDropdown(e) {
        e.preventDefault();
        const d = document.getElementById('profileDropdown');
        d.style.display = d.style.display === 'none' ? 'block' : 'none';
    }

    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('profileDropdownWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            document.getElementById('profileDropdown').style.display = 'none';
        }
    });

    // Intercept dashboard link for tutors under 100%
    document.addEventListener('DOMContentLoaded', function() {
        if (tutorCompletion < 100) {
            document.querySelectorAll('a[href*="dashboard"]').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('completionPopup').style.display = 'flex';
                });
            });
        }
    });
</script>

{{-- Popup --}}
<div id="completionPopup" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:99999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:24px;padding:2rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);text-align:center;">
        <div style="width:60px;height:60px;background:rgba(99,102,241,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <i class="bi bi-lock-fill" style="color:#6366f1;font-size:1.5rem;"></i>
        </div>
        <h5 style="font-weight:800;color:#0f172a;margin-bottom:0.5rem;">Complete Your Profile First</h5>
        <p style="color:#64748b;font-size:0.88rem;margin-bottom:1.25rem;">
            Your profile is <strong style="color:#6366f1;">{{ $navCompletion }}%</strong> complete. You need to reach 100% before accessing the dashboard and other features.
        </p>
        <div style="height:8px;background:#e2e8f0;border-radius:999px;overflow:hidden;margin-bottom:1.25rem;">
            <div style="height:100%;width:{{ $navCompletion }}%;background:linear-gradient(90deg,#6366f1,#4f46e5);border-radius:999px;"></div>
        </div>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('profile.edit') }}" style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border-radius:12px;padding:0.65rem 1.5rem;text-decoration:none;font-size:0.88rem;">
                Complete Profile →
            </a>
            <button onclick="document.getElementById('completionPopup').style.display='none'" style="background:#f1f5f9;color:#64748b;font-weight:700;border:none;border-radius:12px;padding:0.65rem 1.25rem;font-size:0.88rem;cursor:pointer;">
                Close
            </button>
        </div>
    </div>
</div>

@else
<script>
    function toggleProfileDropdown(e) {
        e.preventDefault();
        const d = document.getElementById('profileDropdown');
        d.style.display = d.style.display === 'none' ? 'block' : 'none';
    }
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('profileDropdownWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            document.getElementById('profileDropdown').style.display = 'none';
        }
    });
</script>
@endif
@endauth
