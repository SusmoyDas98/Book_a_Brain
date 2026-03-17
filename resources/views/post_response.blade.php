<!DOCTYPE html>

<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tutor Applications | Book-a-Brain</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<link rel="stylesheet" href="{{asset('css/post_response.css')}}">
<link rel="stylesheet" href="{{asset('css/navbar.css')}}">

</head>

<body>

<!-- NAVBAR -->

<x-navbar />

<!-- FILTER BAR -->

<div class="container">

<div class="filter-bar">

<div class="row g-3 align-items-center">

<div class="col-md-4">

<select id="sortSelect" class="sort-select w-100">
<option value="">Sort By</option>
<option value="rating_desc">Rating High → Low</option>
<option value="rating_asc">Rating Low → High</option>
<option value="salary_desc">Salary High → Low</option>
<option value="salary_asc">Salary Low → High</option>
</select>

</div>

<div class="col-md-4">

<button class="btn btn-outline-success filter-btn w-100" onclick="showShortlisted()">
Show Only Shortlisted
</button>

</div>

<div class="col-md-4">

<button class="btn btn-outline-secondary filter-btn w-100" onclick="resetFilters()">
Reset Filters
</button>

</div>

</div>

</div>

</div>

<!-- PROFILES -->

<div class="container d-flex flex-column align-items-center justify-content-center min-vh-100" id="profilesContainer">
@forelse ($tutorInfos as $tutorInfo)
    @php
        $profilePic = $tutorInfo['tutor_profile_pic'];

        // Check if file exists in public folder, otherwise use default based on gender
        if (! $profilePic || ! file_exists(public_path($profilePic))) {
            $profilePic = $tutorInfo['gender'] === 'Male'
                ? asset('images/default_male.png')
                : asset('images/default_female.png');
        } else {
            $profilePic = asset($profilePic);
        }
    @endphp

    <div class="row align-items-center mb-4 tutor-card {{ $tutorInfo['shortlisted'] ? 'shortlisted' : '' }}"
            data-rating="{{ $tutorInfo['tutor_rating'] }}"
            data-salary="{{ $tutorInfo['expected_salary'] }}"
            >
        <div class="col-md-2 text-center">
            <img src="{{ $profilePic }}" class="profile-img">
        </div>

        <div class="col-md-7">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="tutor-name">{{ $tutorInfo['tutor_name'] }}</div>
                    <div class="rating"><i class="bi bi-star-fill"></i> {{ $tutorInfo['tutor_rating'] }}</div>
                </div>
                <div class="fw-bold text-primary">৳ {{ $tutorInfo['expected_salary'] }}/hr</div>
            </div>

            <div class="mt-2">
                {{-- <span class="info-badge"><b>Subjects:</b> {{ implode(', ', $tutorInfo['preferred_subjects'] ?? []) }}</span>
                <span class="info-badge"><b>Medium:</b> {{ implode(', ', $tutorInfo['preferred_mediums'] ?? []) }}</span> --}}
                {{-- <span class="info-badge"><b>Classes:</b> {{ implode(', ', $tutorInfo['preferred_classes'] ?? []) }}</span> --}}
                <span class="info-badge"><b>Education:</b> {{ $tutorInfo['tutor_educational_institutions']['university'] ?? 'N/A' }}</span>
                <span class="info-badge"><b>Experience:</b> {{ implode(', ', $tutorInfo['tutor_work_experience'] ? array_values($tutorInfo['tutor_work_experience']) : []) }}</span>
                <span class="info-badge"><b>Method:</b> {{ implode(', ', $tutorInfo['teaching_method'] ?? []) }}</span>
                {{-- <span class="info-badge"><b>Availability:</b> {{ implode(', ', $tutorInfo['availability'] ?? []) }}</span> --}}
            </div>
        </div>

        <div class="col-md-3 text-end">
            <a class="btn btn-outline-dark btn-modern cv-btn mb-2 w-100" href="{{ $tutorInfo['cv'] }}" target="_blank">
                <i class="bi bi-eye-fill"></i> View CV
            </a>
            <button class="btn btn-outline-primary btn-modern btn-profile mb-2 w-100">
                <i class="bi bi-person-circle"></i> View Profile
            </button>
            <button class="btn btn-outline-secondary btn-modern mb-2 w-100">
                <i class="bi bi-chat-dots"></i> Message
            </button>
            <button class="btn btn-shortlist btn-modern w-100" onclick="toggleShortlist({{ $loop->index }})">
                <i class="bi bi-check-circle"></i> Shortlist
            </button>
        </div>
    </div>        
@empty
    <p class="text-center py-5" style="font-size:1.8rem;">No responses found.</p>
@endforelse
</div>

<script src="{{asset('js/post_response.js')}}"></script>

</body>
</html>
