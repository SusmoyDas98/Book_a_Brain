<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tutor Applications | Book-a-Brain</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


<link rel="stylesheet" href="{{asset('css/navbar.css')}}">
<link rel="stylesheet" href="{{asset('css/post_response.css')}}">

</head>
<body>

<x-navbar />

<!-- FILTER BAR -->
<div class="filter-bar">
    <div class="d-flex w-100 justify-content-between align-items-center">
        <div style="flex:1; margin-right:10px;">
            <select id="sortSelect" class="sort-select w-100">
                <option value="">Sort By</option>
                <option value="rating_desc">Rating High → Low</option>
                <option value="rating_asc">Rating Low → High</option>
                <option value="salary_desc">Salary High → Low</option>
                <option value="salary_asc">Salary Low → High</option>
            </select>
        </div>
        <div style="flex:1; text-align:center;">
            <button class="btn filter-btn" onclick="showShortlisted()">Show Only Shortlisted</button>
            <button class="btn filter-btn" onclick="resetFilters()">Reset Filters</button>
        </div>
        <div style="flex:1; text-align:right;">
            <button id="saveChangesBtn" type="submit" form="tutorsForm" disabled>
                Save Changes
            </button>
        </div>
    </div>
</div>

<!-- PROFILES -->
<div id="profilesContainer">
<form id="tutorsForm" action = "{{ route('update_response.update') }}" method="POST" >
    {{-- action = "{{ route('update_response.update') }}" method="POST" --}}
    @csrf
    @method('PATCH')
@forelse ($tutorInfos as $tutorInfo)
    @php
        $profilePic = $tutorInfo['tutor_profile_pic'];
        if (!$profilePic || !file_exists(public_path($profilePic))) {
            $profilePic = $tutorInfo['gender'] === 'Male' ? asset('images/default_male.png') : asset('images/default_female.png');
        } else {
            $profilePic = asset($profilePic);
        }
    @endphp

    <div class="tutor-card {{ $tutorInfo['shortlisted'] ? 'shortlisted' : '' }}"
        data-rating="{{ $tutorInfo['tutor_rating'] }}"
        data-salary="{{ $tutorInfo['expected_salary'] }}">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <img src="{{ $profilePic }}" class="profile-img">
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="tutor-name">{{ $tutorInfo['tutor_name'] }}</div>
                        <div class="rating"><i class="bi bi-star-fill"></i> {{ $tutorInfo['tutor_rating'] }}</div>
                    </div>
                    <div class="fw-bold text-primary">৳ {{ $tutorInfo['expected_salary'] }}/hr</div>
                </div>
                <div class="mt-2">
                    <span class="info-badge"><b>Education:</b> {{ $tutorInfo['tutor_educational_institutions']['university'] ?? 'N/A' }}</span>
                    <span class="info-badge"><b>Experience:</b> {{ implode(', ', $tutorInfo['tutor_work_experience'] ? array_values($tutorInfo['tutor_work_experience']) : []) }}</span>
                    <span class="info-badge"><b>Method:</b> {{ implode(', ', $tutorInfo['teaching_method'] ?? []) }}</span>
                </div>
            </div>
            <div class="ms-3 text-end">
                <a class="btn btn-outline-dark btn-modern mb-2 w-100" href="{{ $tutorInfo['cv'] }}" target="_blank"><i class="bi bi-eye-fill"></i> View CV</a>
                <button class="btn btn-outline-primary btn-modern mb-2 w-100"><i class="bi bi-person-circle"></i> View Profile</button>
                <button class="btn btn-outline-secondary btn-modern mb-2 w-100"><i class="bi bi-chat-dots"></i> Message</button>
                <input 
                    type="hidden" 
                    name="{{ $tutorInfo['id'] }}" 
                    value="{{ $tutorInfo['shortlisted'] ? 1 : 0 }}" 
                    class="shortlist-input">

                <button type="button" 
                        class="btn btn-shortlist btn-modern w-100 {{ $tutorInfo['shortlisted'] ? 'remove' : '' }}" 
                        onclick="toggleShortlist(this, {{ $tutorInfo['id'] }})">
                    <i class="bi bi-check-circle"></i> {{ $tutorInfo['shortlisted'] ? 'Remove' : 'Shortlist' }}
                </button>
            </div>
        </div>
    </div>
@empty
@endforelse
</form>
</div>
<script src="{{ asset('js/post_response.js') }}"></script>
</body>
</html>