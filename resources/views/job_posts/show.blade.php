<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Job Applicants | Book-a-Brain</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<link rel="stylesheet" href="{{ asset('css/job_posts.css') }}">

</head>

<body>

<x-navbar/>

<div class="container page-wrapper">

  @if(session('success'))
    <div class="alert-success-custom">
      <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="alert-danger-custom">
      <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    </div>
  @endif

  {{-- Job Details Section --}}
  <div class="job-card mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
      <h3 class="fw-bold mb-1">{{ $jobPost->title }}</h3>
      <span class="status-badge badge-{{ strtolower($jobPost->status) }}">
        {{ $jobPost->status }}
      </span>
    </div>
    <p class="text-secondary mb-2" style="font-size:0.9rem">
      {{ $jobPost->subject }} &nbsp;·&nbsp; {{ $jobPost->class_level }}
    </p>
    <div class="fw-bold text-primary fs-5 mb-2">৳{{ number_format($jobPost->expected_salary) }}/month</div>
    <div class="d-flex flex-wrap gap-3 mb-2" style="font-size:0.85rem;color:#475569">
      <span><i class="bi bi-geo-alt me-1"></i>{{ $jobPost->location }}</span>
      <span><i class="bi bi-translate me-1"></i>{{ $jobPost->medium }}</span>
      <span><i class="bi bi-laptop me-1"></i>{{ $jobPost->mode }}</span>
    </div>
    <div class="mb-2" style="font-size:0.85rem;color:#475569">
      <i class="bi bi-people me-1"></i>Shortlisted: {{ $jobPost->shortlisted_count }}/5 tutors
    </div>
    @if($jobPost->description)
      <p class="mt-2 text-secondary small">{{ $jobPost->description }}</p>
    @endif
    <div class="d-flex gap-2 mt-3">
      @if(in_array($jobPost->status, ['Open','Shortlisting']))
        <a href="{{ route('job_posts.edit', $jobPost) }}"
           class="btn btn-outline-primary btn-sm" style="border-radius:10px;font-weight:600">
          <i class="bi bi-pencil me-1"></i>Edit
        </a>
      @endif
      <a href="{{ route('job_posts.index') }}"
         class="btn btn-outline-secondary btn-sm" style="border-radius:10px;font-weight:600">
        <i class="bi bi-arrow-left me-1"></i>Back to My Posts
      </a>
    </div>
  </div>

  {{-- Applicants Section --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">Applicants ({{ count($responses) }})</h4>
    @if($jobPost->shortlisted_count >= 2)
      <a href="{{ route('post_response_redirect') }}"
         class="btn-success-custom">
        <i class="bi bi-bar-chart me-1"></i>View Shortlisted (Feature 9)
      </a>
    @endif
  </div>

  @if($responses->isEmpty())
    <div class="empty-state">
      <i class="bi bi-inbox"></i>
      <h5>No applications yet</h5>
      <p>Applications from tutors will appear here.</p>
    </div>
  @else
    @foreach($responses as $response)
    <div class="applicant-card {{ $response->shortlisted ? 'shortlisted-card' : '' }}">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div style="flex:1">
          <div class="d-flex align-items-center gap-2 mb-1">
            <h5 class="fw-bold mb-0">{{ $response->tutor_name }}</h5>
            <span class="status-badge badge-{{ strtolower($response->status) }}">
              {{ $response->status }}
            </span>
          </div>
          <div class="text-secondary small mb-2">
            {{ $response->gender ?? 'N/A' }} &nbsp;·&nbsp;
            <i class="bi bi-star-fill text-warning me-1"></i>{{ $response->tutor_rating ?? 'N/A' }}/5 &nbsp;·&nbsp;
            ৳{{ number_format($response->expected_salary) }}/month
          </div>
          @if(!empty($response->preferred_subjects))
          <div class="mb-2">
            <span class="text-secondary small">Subjects: </span>
            <span class="small">{{ implode(', ', $response->preferred_subjects) }}</span>
          </div>
          @endif
          <div class="p-3 rounded-3 mb-2" style="background:#f1f5f9;font-size:0.85rem;border-left:3px solid var(--brand-primary)">
            "{{ $response->application_message }}"
          </div>
          <div class="text-secondary small">
            Applied: {{ $response->created_at->format('d M Y') }}
            @if($response->cv)
              &nbsp;·&nbsp;
              <a href="{{ asset('storage/' . $response->cv) }}" target="_blank"
                 class="text-primary" style="font-weight:600">
                <i class="bi bi-file-earmark-pdf me-1"></i>View CV
              </a>
            @endif
          </div>
        </div>
        <div class="d-flex flex-column gap-2" style="min-width:160px">
          @if($response->status === 'Pending' && !$response->shortlisted && $jobPost->shortlisted_count < 5)
          <form method="POST" action="{{ route('job_posts.shortlist', $jobPost) }}">
            @csrf
            <input type="hidden" name="response_id" value="{{ $response->id }}">
            <button type="submit" class="btn-success-custom w-100">
              <i class="bi bi-check-circle me-1"></i>Shortlist
            </button>
          </form>
          @endif
          @if($response->shortlisted)
          <form method="POST" action="{{ route('job_posts.remove_shortlist', $jobPost) }}">
            @csrf
            <input type="hidden" name="response_id" value="{{ $response->id }}">
            <button type="submit" class="btn-warning-custom w-100">
              <i class="bi bi-x-circle me-1"></i>Remove
            </button>
          </form>
          @endif
          @if($response->status === 'Pending' && !$response->shortlisted)
          <form method="POST" action="{{ route('job_posts.reject', $jobPost) }}">
            @csrf
            <input type="hidden" name="response_id" value="{{ $response->id }}">
            <button type="submit" class="btn-danger-custom w-100"
              onclick="return confirm('Reject this applicant?')">
              <i class="bi bi-x-lg me-1"></i>Reject
            </button>
          </form>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  @endif

</div>

</body>
</html>
