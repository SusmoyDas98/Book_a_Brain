<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tutorProfile->name ?? $tutor->name }} | Book-a-Brain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <style>
        :root {
            --brand-primary: #6366f1;
            --brand-dark: #0f172a;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--brand-dark);
            min-height: 100vh;
            background-image:
                linear-gradient(135deg, #ffffff 0%, #dbeafe 50%, #a2ccff 100%),
                linear-gradient(to right, rgba(71,85,105,0.3) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(71,85,105,0.3) 1px, transparent 1px),
                linear-gradient(to right, rgba(99,102,241,0.15) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(99,102,241,0.15) 1px, transparent 1px),
                radial-gradient(at 15% 15%, rgba(99,102,241,0.15) 0px, transparent 40%),
                radial-gradient(at 85% 85%, rgba(14,165,233,0.12) 0px, transparent 40%);
            background-size: auto, 60px 60px, 60px 60px, 15px 15px, 15px 15px, auto, auto;
            background-attachment: fixed;
        }
        .profile-card {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(18px);
            border-radius: 24px;
            border: 2px solid #e2e8f0;
            box-shadow: 0 8px 30px rgba(0,0,0,0.07);
            padding: 1.75rem;
            margin-bottom: 1rem;
            transition: 0.2s;
        }
        .profile-card:hover {
            border-color: rgba(99,102,241,0.3);
            box-shadow: 0 12px 40px rgba(99,102,241,0.1);
        }
        .section-title {
            font-weight: 800;
            color: #0f172a;
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        .meta-label {
            font-size: 0.72rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }
        .meta-value {
            color: #1e293b;
            font-weight: 500;
            font-size: 0.92rem;
            margin-bottom: 0;
        }
        .tag {
            display: inline-block;
            background: rgba(99,102,241,0.08);
            color: #6366f1;
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 3px 12px;
            margin: 3px;
        }
        .hire-btn {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            font-weight: 700;
            border: none;
            border-radius: 14px;
            padding: 0.8rem 2rem;
            font-size: 0.95rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 6px 20px rgba(99,102,241,0.35);
            transition: 0.2s;
        }
        .hire-btn:hover {
            opacity: 0.9;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<x-navbar/>

<div style="padding: 3rem 0 5rem;">
<div class="container" style="max-width: 960px;">

    <div class="row g-4">

        {{-- LEFT: Identity --}}
        <div class="col-lg-4">
            <div class="profile-card text-center" style="position:sticky;top:2rem;">

                {{-- Profile Picture --}}
                @php
                    $pic = optional($tutorProfile)->profile_picture
                        ? asset('storage/' . $tutorProfile->profile_picture)
                        : asset('images/default_avatar.png');
                @endphp
                <img src="{{ $pic }}" alt="{{ $tutorProfile->name ?? $tutor->name }}"
                     style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0;margin-bottom:1rem;">

                {{-- Name --}}
                <h4 style="font-weight:800;color:#0f172a;margin-bottom:0.25rem;">
                    {{ $tutorProfile->name ?? $tutor->name }}
                </h4>

                {{-- Verified Badge --}}
                @if(optional($tutorProfile)->verification_status === 'APPROVED')
                    <div class="mb-2">
                        <span style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border-radius:999px;font-size:0.72rem;font-weight:700;padding:4px 14px;display:inline-flex;align-items:center;gap:5px;box-shadow:0 4px 12px rgba(99,102,241,0.3);">
                            <i class="bi bi-patch-check-fill"></i> Verified Tutor
                        </span>
                    </div>
                @endif

                {{-- Rating --}}
                @php $rating = $tutor->ratings ?? 0; @endphp
                <div class="mb-3">
                    <span style="color:#f59e0b;font-size:0.95rem;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= round($rating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </span>
                    <span style="color:#64748b;font-size:0.82rem;margin-left:4px;">{{ number_format($rating, 1) }}</span>
                </div>

                <hr style="border-color:#e2e8f0;">

                {{-- Core Info --}}
                <div class="text-start">
                    @if(optional($tutorProfile)->contact_no)
                        <p class="meta-label"><i class="bi bi-telephone me-1"></i>Phone</p>
                        <p class="meta-value mb-3">{{ $tutorProfile->contact_no }}</p>
                    @endif
                    @if(optional($tutorProfile)->gender)
                        <p class="meta-label"><i class="bi bi-person me-1"></i>Gender</p>
                        <p class="meta-value mb-3">{{ $tutorProfile->gender }}</p>
                    @endif
                    @if(optional($tutorProfile)->expected_salary)
                        <p class="meta-label"><i class="bi bi-cash me-1"></i>Expected Salary</p>
                        <p class="meta-value mb-3" style="font-size:1.1rem;font-weight:800;color:#6366f1;">
                            ৳{{ number_format($tutorProfile->expected_salary) }}/month
                        </p>
                    @endif
                </div>

                {{-- Hire Button (only for guardians) --}}
                @auth
                    @if(strtolower(Auth::user()->role) === 'guardian')
                        <a href="{{ route('contracts.create', ['tutor_id' => $tutor->tutor_id]) }}" class="hire-btn w-100 text-center mt-2">
                            <i class="bi bi-person-check-fill me-2"></i>Hire This Tutor
                        </a>
                    @endif
                @endauth

                {{-- CV Download --}}
                @if(optional($tutorProfile)->cv)
                    <a href="{{ asset('storage/' . $tutorProfile->cv) }}" target="_blank"
                       style="display:block;margin-top:0.75rem;background:#f8fafc;border:2px solid #e2e8f0;border-radius:14px;padding:0.65rem 1rem;color:#6366f1;font-weight:700;font-size:0.85rem;text-decoration:none;text-align:center;transition:0.2s;"
                       onmouseover="this.style.borderColor='#6366f1'" onmouseout="this.style.borderColor='#e2e8f0'">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Download CV
                    </a>
                @endif

            </div>
        </div>

        {{-- RIGHT: Details --}}
        <div class="col-lg-8">

            {{-- Teaching Details --}}
            <div class="profile-card">
                <p class="section-title"><i class="bi bi-journal-text me-2" style="color:#6366f1;"></i>Teaching Details</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="meta-label">Teaching Method</p>
                        <p class="meta-value">
                            @php $method = optional($tutorProfile)->teaching_method; @endphp
                            @if(is_array($method))
                                @foreach($method as $m)
                                    <span class="tag">{{ $m }}</span>
                                @endforeach
                            @else
                                {{ $method ?: '—' }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="meta-label">Availability</p>
                        <p class="meta-value">
                            @php $avail = optional($tutorProfile)->availability; @endphp
                            @if(is_array($avail))
                                @foreach($avail as $a)
                                    <span class="tag">{{ $a }}</span>
                                @endforeach
                            @else
                                {{ $avail ?: '—' }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="meta-label">Preferred Mediums</p>
                        <p class="meta-value">
                            @php $mediums = optional($tutorProfile)->preferred_mediums; @endphp
                            @if(is_array($mediums))
                                @foreach($mediums as $m)
                                    <span class="tag">{{ $m }}</span>
                                @endforeach
                            @else
                                {{ $mediums ?: '—' }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="meta-label">Subjects</p>
                        <p class="meta-value">
                            @php $subjects = optional($tutorProfile)->preferred_subjects; @endphp
                            @if(is_array($subjects))
                                @foreach($subjects as $s)
                                    <span class="tag">{{ $s }}</span>
                                @endforeach
                            @else
                                {{ $subjects ?: '—' }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="meta-label">Classes</p>
                        <p class="meta-value">
                            @php $classes = optional($tutorProfile)->preferred_classes; @endphp
                            @if(is_array($classes))
                                @foreach($classes as $c)
                                    <span class="tag">Class {{ $c }}</span>
                                @endforeach
                            @else
                                {{ $classes ?: '—' }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Background --}}
            <div class="profile-card">
                <p class="section-title"><i class="bi bi-mortarboard me-2" style="color:#6366f1;"></i>Background</p>
                <p class="meta-label">Educational Institutions</p>
                @php $edu = optional($tutorProfile)->educational_institutions; @endphp
                @if(is_array($edu))
                    <ul style="color:#1e293b;font-size:0.9rem;padding-left:1.25rem;margin-bottom:1rem;">
                        @foreach($edu as $key => $val)
                            <li><strong>{{ ucfirst($key) }}:</strong> {{ $val }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="meta-value mb-3">{{ $edu ?: '—' }}</p>
                @endif

                <p class="meta-label">Work Experience</p>
                @php $work = optional($tutorProfile)->work_experience; @endphp
                @if(is_array($work))
                    <ul style="color:#1e293b;font-size:0.9rem;padding-left:1.25rem;margin:0;">
                        @foreach($work as $period => $desc)
                            <li><strong>{{ $period }}:</strong> {{ $desc }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="meta-value mb-0">{{ $work ?: '—' }}</p>
                @endif
            </div>

            {{-- Ratings & Reviews --}}
            <div class="profile-card">
                <p class="section-title"><i class="bi bi-star me-2" style="color:#f59e0b;"></i>Ratings & Reviews</p>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <p style="font-size:3rem;font-weight:800;color:#0f172a;margin:0;line-height:1;">{{ number_format($rating, 1) }}</p>
                    <div>
                        <div style="color:#f59e0b;font-size:1.2rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= round($rating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                        <p style="color:#94a3b8;font-size:0.8rem;margin:0.2rem 0 0;">Based on guardian reviews</p>
                    </div>
                </div>
                @if(optional($tutor)->review)
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:1rem;">
                        <p style="color:#1e293b;font-size:0.9rem;margin:0;font-style:italic;">"{{ $tutor->review }}"</p>
                    </div>
                @else
                    <p style="color:#94a3b8;font-size:0.85rem;margin:0;">No reviews yet.</p>
                @endif
            </div>

        </div>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
