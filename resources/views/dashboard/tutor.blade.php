@extends('layouts.app')

@section('content')

@php
    $user              = Auth::user();
    $tutorProfile      = \App\Models\TutorProfile::where('tutor_id', $user->id)->first();
    $verificationDocs  = \App\Models\VerificationDocument::where('tutor_id', $user->id)->get();
    $activeContracts   = \App\Models\TuitionContract::where('tutor_id', $user->id)->where('status', 'ACTIVE')->with('guardian')->get();
    $pendingContracts  = \App\Models\TuitionContract::where('tutor_id', $user->id)->where('status', 'PENDING')->with('guardian')->get();
    $totalEarnings     = \App\Models\TuitionContract::where('tutor_id', $user->id)->where('status', 'ACTIVE')->sum('salary');
    $totalJobs         = \App\Models\TuitionContract::where('tutor_id', $user->id)->whereIn('status', ['ACTIVE','ENDED'])->count();

    // Completion
    $fields = [
        $user->name, $user->email,
        optional($tutorProfile)->contact_no,
        optional($tutorProfile)->gender,
        optional($tutorProfile)->profile_picture,
        optional($tutorProfile)->cv,
        optional($tutorProfile)->educational_institutions,
        optional($tutorProfile)->work_experience,
        optional($tutorProfile)->teaching_method,
        optional($tutorProfile)->availability,
        optional($tutorProfile)->preferred_mediums,
        optional($tutorProfile)->preferred_subjects,
        optional($tutorProfile)->expected_salary,
        $verificationDocs->firstWhere('doc_type','NID')?->file_path,
        $verificationDocs->firstWhere('doc_type','OCCUPATION_CARD')?->file_path,
    ];
    $completionPct = round((collect($fields)->filter(fn($v) => !is_null($v) && $v !== '')->count() / count($fields)) * 100);
@endphp

<div style="min-height:100vh; padding: 2.5rem 0 5rem;">
<div class="container" style="max-width:1100px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.3em;color:#6366f1;font-weight:700;">Book-a-Brain</p>
            <h1 style="color:#0f172a;font-size:2rem;font-weight:800;margin-bottom:0.25rem;">
                Welcome back, {{ $user->name }} 👋
            </h1>
            <p style="color:#64748b;margin:0;">Manage your tutoring career from here.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('profile.edit') }}"
               style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border:none;border-radius:14px;padding:0.7rem 1.4rem;text-decoration:none;font-size:0.88rem;box-shadow:0 6px 20px rgba(99,102,241,0.3);">
                <i class="bi bi-pencil me-2"></i>Edit Profile
            </a>
            <a href="{{ route('contracts.tutor') }}"
               style="background:white;color:#6366f1;font-weight:700;border:2px solid #6366f1;border-radius:14px;padding:0.7rem 1.4rem;text-decoration:none;font-size:0.88rem;">
                <i class="bi bi-file-earmark-text me-2"></i>My Contracts
            </a>
        </div>
    </div>

    {{-- PROFILE COMPLETION REMINDER --}}
    @if($completionPct < 100)
    <div style="background:linear-gradient(135deg,rgba(99,102,241,0.08),rgba(79,70,229,0.05));border:2px solid rgba(99,102,241,0.2);border-radius:20px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <p style="font-weight:700;color:#0f172a;margin:0 0 0.2rem;">
                    <i class="bi bi-exclamation-circle me-2" style="color:#6366f1;"></i>Complete your profile — {{ $completionPct }}% done
                </p>
                <p style="color:#64748b;font-size:0.82rem;margin:0;">A complete profile gets 3x more responses from guardians.</p>
            </div>
            <a href="{{ route('profile.edit') }}"
               style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border-radius:12px;padding:0.55rem 1.2rem;text-decoration:none;font-size:0.83rem;white-space:nowrap;">
                Complete Now →
            </a>
        </div>
        <div style="height:6px;background:rgba(99,102,241,0.15);border-radius:999px;overflow:hidden;margin-top:0.75rem;">
            <div style="height:100%;width:{{ $completionPct }}%;background:linear-gradient(90deg,#6366f1,#4f46e5);border-radius:999px;transition:width 0.6s;"></div>
        </div>
    </div>
    @endif

    {{-- STATS CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div style="background:white;border:2px solid #e2e8f0;border-radius:20px;padding:1.25rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);transition:0.2s;" onmouseover="this.style.borderColor='#6366f1';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='translateY(0)'">
                <div style="width:42px;height:42px;background:rgba(99,102,241,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                    <i class="bi bi-currency-dollar" style="color:#6366f1;font-size:1.2rem;"></i>
                </div>
                <p style="font-size:1.6rem;font-weight:800;color:#0f172a;margin:0;line-height:1;">৳{{ number_format($totalEarnings) }}</p>
                <p style="color:#64748b;font-size:0.8rem;margin:0.2rem 0 0;font-weight:500;">Monthly Earnings</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div style="background:white;border:2px solid #e2e8f0;border-radius:20px;padding:1.25rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);transition:0.2s;" onmouseover="this.style.borderColor='#6366f1';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='translateY(0)'">
                <div style="width:42px;height:42px;background:rgba(34,197,94,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                    <i class="bi bi-activity" style="color:#16a34a;font-size:1.2rem;"></i>
                </div>
                <p style="font-size:1.8rem;font-weight:800;color:#0f172a;margin:0;line-height:1;">{{ $activeContracts->count() }}</p>
                <p style="color:#64748b;font-size:0.8rem;margin:0.2rem 0 0;font-weight:500;">Active Jobs</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div style="background:white;border:2px solid #e2e8f0;border-radius:20px;padding:1.25rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);transition:0.2s;" onmouseover="this.style.borderColor='#6366f1';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='translateY(0)'">
                <div style="width:42px;height:42px;background:rgba(245,158,11,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                    <i class="bi bi-hourglass-split" style="color:#d97706;font-size:1.2rem;"></i>
                </div>
                <p style="font-size:1.8rem;font-weight:800;color:#0f172a;margin:0;line-height:1;">{{ $pendingContracts->count() }}</p>
                <p style="color:#64748b;font-size:0.8rem;margin:0.2rem 0 0;font-weight:500;">Pending Requests</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div style="background:white;border:2px solid #e2e8f0;border-radius:20px;padding:1.25rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);transition:0.2s;" onmouseover="this.style.borderColor='#6366f1';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='translateY(0)'">
                <div style="width:42px;height:42px;background:rgba(14,165,233,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                    <i class="bi bi-briefcase" style="color:#0284c7;font-size:1.2rem;"></i>
                </div>
                <p style="font-size:1.8rem;font-weight:800;color:#0f172a;margin:0;line-height:1;">{{ $totalJobs }}</p>
                <p style="color:#64748b;font-size:0.8rem;margin:0.2rem 0 0;font-weight:500;">Total Jobs</p>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ACTIVE CONTRACTS + SESSION LOGS --}}
        <div class="col-lg-7">
            <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1rem;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p style="font-weight:800;color:#0f172a;font-size:1rem;margin:0;">
                        <i class="bi bi-activity me-2" style="color:#6366f1;"></i>Active Contracts
                    </p>
                    <a href="{{ route('contracts.tutor') }}" style="color:#6366f1;font-size:0.8rem;font-weight:600;text-decoration:none;">View all →</a>
                </div>
                @forelse($activeContracts as $contract)
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:1rem;margin-bottom:0.75rem;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p style="font-weight:700;color:#0f172a;margin:0 0 0.2rem;">{{ $contract->guardian->name }}</p>
                                <p style="color:#64748b;font-size:0.82rem;margin:0 0 0.5rem;">
                                    <i class="bi bi-book me-1"></i>{{ $contract->subject }}
                                    &nbsp;·&nbsp; ৳{{ number_format($contract->salary) }}/mo
                                </p>
                                @php
                                    $lastLog = $contract->sessionLogs()->first();
                                @endphp
                                @if($lastLog)
                                    <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.72rem;font-weight:700;padding:2px 10px;">
                                        Last logged: {{ $lastLog->week_start->format('d M') }}
                                    </span>
                                @else
                                    <span style="background:rgba(245,158,11,0.1);color:#d97706;border-radius:999px;font-size:0.72rem;font-weight:700;padding:2px 10px;">
                                        No sessions logged yet
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('contracts.show', $contract) }}"
                               style="background:rgba(99,102,241,0.08);color:#6366f1;border-radius:10px;padding:5px 12px;font-size:0.78rem;font-weight:700;text-decoration:none;white-space:nowrap;">
                                Log →
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:2rem 0;">
                        <i class="bi bi-inbox" style="font-size:2rem;color:#cbd5e1;"></i>
                        <p style="color:#94a3b8;margin-top:0.75rem;font-size:0.85rem;">No active contracts yet.</p>
                    </div>
                @endforelse
            </div>

            {{-- PENDING HIRE REQUESTS --}}
            @if($pendingContracts->count())
            <div style="background:white;border:2px solid #fde68a;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
                    <i class="bi bi-bell me-2" style="color:#d97706;"></i>Pending Hire Requests
                    <span style="background:rgba(245,158,11,0.15);color:#d97706;border-radius:999px;font-size:0.72rem;font-weight:700;padding:2px 10px;margin-left:0.5rem;">{{ $pendingContracts->count() }}</span>
                </p>
                @foreach($pendingContracts as $contract)
                    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:14px;padding:0.85rem 1rem;margin-bottom:0.5rem;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <p style="font-weight:700;color:#0f172a;font-size:0.88rem;margin:0;">{{ $contract->guardian->name }} wants to hire you</p>
                                <p style="color:#64748b;font-size:0.78rem;margin:0;">{{ $contract->subject }} · ৳{{ number_format($contract->salary) }}/mo</p>
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('contracts.accept', $contract) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" style="background:#16a34a;color:white;border:none;border-radius:10px;padding:5px 14px;font-size:0.78rem;font-weight:700;cursor:pointer;">Accept</button>
                                </form>
                                <form action="{{ route('contracts.decline', $contract) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" style="background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;border-radius:10px;padding:5px 14px;font-size:0.78rem;font-weight:700;cursor:pointer;">Decline</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-5">

            {{-- QUICK LINKS --}}
            <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1rem;">
                <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
                    <i class="bi bi-lightning-charge me-2" style="color:#6366f1;"></i>Quick Actions
                </p>
                <div class="d-flex flex-column gap-2">
                    @foreach([
                        ['route' => 'profile.edit', 'icon' => 'bi-person-circle', 'label' => 'Edit Profile', 'sub' => 'Update your details'],
                        ['route' => 'contracts.tutor', 'icon' => 'bi-file-earmark-text', 'label' => 'My Contracts', 'sub' => 'View all contracts'],
                        ['route' => 'profile.show', 'icon' => 'bi-eye', 'label' => 'View Profile', 'sub' => 'See your public profile'],
                    ] as $link)
                        <a href="{{ route($link['route']) }}"
                           style="display:flex;align-items:center;gap:0.75rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:0.75rem 1rem;text-decoration:none;transition:0.2s;"
                           onmouseover="this.style.borderColor='#6366f1';this.style.background='rgba(99,102,241,0.05)'"
                           onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc'">
                            <div style="width:36px;height:36px;background:rgba(99,102,241,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi {{ $link['icon'] }}" style="color:#6366f1;"></i>
                            </div>
                            <div>
                                <p style="color:#0f172a;font-weight:700;font-size:0.85rem;margin:0;">{{ $link['label'] }}</p>
                                <p style="color:#94a3b8;font-size:0.75rem;margin:0;">{{ $link['sub'] }}</p>
                            </div>
                            <i class="bi bi-chevron-right ms-auto" style="color:#cbd5e1;font-size:0.75rem;"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- APPLICATIONS STATUS PLACEHOLDER --}}
            <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1rem;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p style="font-weight:800;color:#0f172a;font-size:1rem;margin:0;">
                        <i class="bi bi-send me-2" style="color:#6366f1;"></i>My Applications
                    </p>
                    <span style="background:rgba(245,158,11,0.1);color:#d97706;border-radius:999px;font-size:0.7rem;font-weight:700;padding:2px 10px;">Coming Soon</span>
                </div>
                <div style="text-align:center;padding:1.5rem 0;">
                    <i class="bi bi-send-check" style="font-size:2rem;color:#cbd5e1;"></i>
                    <p style="color:#94a3b8;font-size:0.83rem;margin:0.5rem 0 0;">Application tracking will appear here once job posts are available.</p>
                </div>
            </div>

            {{-- RATINGS PLACEHOLDER --}}
            <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:0.75rem;">
                    <i class="bi bi-star me-2" style="color:#f59e0b;"></i>Your Rating
                </p>
                @php $rating = \App\Models\Tutor::where('tutor_id', $user->id)->value('ratings') ?? 0; @endphp
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <p style="font-size:2.5rem;font-weight:800;color:#0f172a;margin:0;line-height:1;">{{ number_format($rating, 1) }}</p>
                    <div>
                        <div style="color:#f59e0b;font-size:1rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= round($rating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                        <p style="color:#94a3b8;font-size:0.75rem;margin:0.2rem 0 0;">Based on guardian reviews</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
</div>

@endsection
