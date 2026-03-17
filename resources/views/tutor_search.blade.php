<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Find Tutors | Book-a-Brain</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<link rel="stylesheet" href="{{asset('css/tutor_search.css')}}">

</head>

<body>

<!-- Navbar -->
<x-navbar/>

<!-- Filter Area -->
<section class="search-hero">
  <div class="container">
    <div class="filter-card">

      <!-- Single Row: Location | Class | Salary | Rating -->
      <div class="row g-3 mb-4">
        {{-- <div class="col-md-3">
          <label class="filter-group-label" for="locationInput">Location</label>
          <input id="locationInput" class="form-control-modern w-100" placeholder="Dhanmondi">
        </div> --}}

        <div class="col-md-2">
          <label class="filter-group-label" for="classSelect">Class</label>
          <select class="form-control-modern w-100" id="classSelect"></select>
        </div>
        <div class="col-md-2">
          <label class="filter-group-label" for="minSalaryInput">Min Salary</label>
          <input type="number" id="minSalaryInput" class="form-control-modern w-100" placeholder="৳">
        </div>
        <div class="col-md-2">
          <label class="filter-group-label" for="maxSalaryInput">Max Salary</label>
          <input type="number" id="maxSalaryInput" class="form-control-modern w-100" placeholder="৳">
        </div>
        <div class="col-md-1">
          <label class="filter-group-label" for="minRatingInput">Min ★</label>
          <input type="number" step="0.1" id="minRatingInput" class="form-control-modern w-100">
        </div>
        <div class="col-md-2">
          <label class="filter-group-label" for="maxRatingInput">Max ★</label>
          <input type="number" step="0.1" id="maxRatingInput" class="form-control-modern w-100">
        </div>
        <div class="col-md-3">
          <label class="filter-group-label">Medium</label>
          <label class="subject-tag">
            <input class="medium-checkbox" type="checkbox" value="Bangla">
            Bangla
          </label>
          <label class="subject-tag">
            <input class="medium-checkbox" type="checkbox" value="English">
            English
          </label>
        </div>        
      </div>

      <!-- Subjects Full Width -->
      <div class="row mb-4">
        <div class="col-12">
          <label class="filter-group-label">Subjects</label>
          <div class="subject-checkboxes" id="subjectCheckboxes">
            <label class="subject-tag"><input type="checkbox" value="Physics" class="subject-checkbox"> Physics</label>
            <label class="subject-tag"><input type="checkbox" value="Chemistry" class="subject-checkbox"> Chemistry</label>
            <label class="subject-tag"><input type="checkbox" value="Math" class="subject-checkbox"> Math</label>
            <label class="subject-tag"><input type="checkbox" value="Biology" class="subject-checkbox"> Biology</label>
            <label class="subject-tag"><input type="checkbox" value="English" class="subject-checkbox"> English</label>
            <label class="subject-tag"><input type="checkbox" value="ICT" class="subject-checkbox"> ICT</label>
            <label class="subject-tag"><input type="checkbox" value="Accounting" class="subject-checkbox"> Accounting</label>
            <label class="subject-tag"><input type="checkbox" value="Finance" class="subject-checkbox"> Finance</label>
            <label class="subject-tag"><input type="checkbox" value="Business Studies" class="subject-checkbox"> Business Studies</label>
            <label class="subject-tag"><input type="checkbox" value="Economics" class="subject-checkbox"> Economics</label>
            <label class="subject-tag"><input type="checkbox" value="Geography" class="subject-checkbox"> Geography</label>
            <label class="subject-tag"><input type="checkbox" value="History" class="subject-checkbox"> History</label>
            <label class="subject-tag"><input type="checkbox" value="Sociology" class="subject-checkbox"> Sociology</label>

            <div class="d-flex align-items-center gap-2 mt-2">
              <label class="subject-tag">
                <input type="checkbox" value="Others" id="otherSubjectCheckbox" class="subject-checkbox"> Others
              </label>
              <input type="text" id="otherSubjectsInput" class="form-control-modern" style="width:180px;padding:6px 10px;font-size:.8rem" placeholder="Type subject">
            </div>
          </div>
        </div>
      </div>

      <!-- Search + Apply -->
      <div class="row">
        <div class="col-md-8">
          <label class="filter-group-label" for="tutorNameInput">Search by Name</label>
          <input class="form-control-modern w-100" id="tutorNameInput" placeholder="Search tutor name">
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button class="btn apply-btn w-100 py-3" id="applyFilterButton" onclick="scrollToResults()">APPLY FILTERS</button>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Results -->
<div class="container results-section" id="resultsAnchor">
  {{-- <h4 class="mb-3 fw-bold" >Elite Matches</h4> --}}

  <div class="scroll-wrapper" style="position:relative;">
    {{-- <div class="scroll-btn btn-prev" onclick="sideScroll(-300)"><i class="bi bi-chevron-left"></i></div>
    <div class="scroll-btn btn-next" onclick="sideScroll(300)"><i class="bi bi-chevron-right"></i></div> --}}

    <div class="tutor-scroll-container" id="profileGrid"></div>
  </div>

</div>

<script src="{{asset('js/tutor_search.js')}}"></script>
{{-- <script src="{{asset('js/tutor_search_handler.js')}}"></script> --}}

</body>
</html>