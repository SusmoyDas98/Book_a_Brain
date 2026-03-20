<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Browse Jobs | Book-a-Brain</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<link rel="stylesheet" href="{{ asset('css/job_posts.css') }}">
<link rel="stylesheet" href="{{ asset('css/browse_jobs.css') }}">

</head>

<body>

<x-navbar/>

<div class="container browse-hero">

  <div class="page-header">
    <h1 class="page-title">Browse Tuition Jobs</h1>
    <a href="{{ route('applications.index') }}"
       class="btn btn-outline-primary btn-sm" style="border-radius:10px;font-weight:600">
      <i class="bi bi-list-check me-1"></i>My Applications
    </a>
  </div>

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

  @if($jobPosts->isEmpty())
    <div class="empty-state">
      <i class="bi bi-briefcase"></i>
      <h5>No open jobs right now</h5>
      <p>Check back later for new tuition opportunities.</p>
    </div>
  @else
    @foreach($jobPosts as $post)
    <div class="browse-card">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
          <h4 class="fw-bold mb-1">{{ $post->title }}</h4>
          <p class="text-secondary mb-2 small">
            {{ $post->subject }} &nbsp;·&nbsp; {{ $post->class_level }}
          </p>
        </div>
        <div class="fw-bold text-primary fs-5">
          ৳{{ number_format($post->expected_salary) }}/month
        </div>
      </div>

      <div class="d-flex flex-wrap gap-3 mt-2 mb-3" style="font-size:0.85rem;color:#475569">
        <span><i class="bi bi-geo-alt me-1"></i>{{ $post->location }}</span>
        <span><i class="bi bi-translate me-1"></i>{{ $post->medium }}</span>
        <span><i class="bi bi-laptop me-1"></i>{{ $post->mode }}</span>
        <span><i class="bi bi-person me-1"></i>Posted by: {{ $post->guardian->name ?? 'Guardian' }}</span>
        <span><i class="bi bi-calendar3 me-1"></i>{{ $post->created_at->format('d M Y') }}</span>
      </div>

      @if($post->description)
      <p class="text-secondary small mb-3">
        {{ \Illuminate\Support\Str::limit($post->description, 120) }}
      </p>
      @endif

      @if(in_array($post->id, $appliedPostIds))
        <button disabled
          style="background:#9ca3af;color:white;padding:8px 20px;border:none;
                 border-radius:10px;font-weight:600;cursor:not-allowed;font-family:inherit">
          <i class="bi bi-check-circle me-1"></i>Already Applied
        </button>
      @else
        <button type="button"
          id="btn-{{ $post->id }}"
          onclick="toggleApplyForm({{ $post->id }})"
          class="btn-primary-custom">
          <i class="bi bi-send me-2"></i>Apply for this Job
        </button>

        <div class="apply-form-section" id="form-{{ $post->id }}">
          <form method="POST" action="{{ route('jobs.apply', $post) }}">
            @csrf
            <label class="form-label-custom">WHY ARE YOU A GOOD FIT?</label>
            <textarea name="application_message" rows="3"
              class="form-control-custom"
              placeholder="Describe your qualifications and why you want this job (minimum 20 characters)">{{ old('application_message') }}</textarea>
            @error('application_message')
              <div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>
            @enderror
            <div class="d-flex gap-2 mt-3">
              <button type="submit" class="btn-success-custom">
                <i class="bi bi-check-lg me-1"></i>Submit Application
              </button>
              <button type="button" onclick="toggleApplyForm({{ $post->id }})"
                style="background:#6b7280;color:white;padding:8px 18px;border:none;
                       border-radius:10px;font-weight:600;cursor:pointer;font-family:inherit">
                Cancel
              </button>
            </div>
          </form>
        </div>
      @endif
    </div>
    @endforeach
  @endif

</div>

<script src="{{ asset('js/browse_jobs.js') }}"></script>

</body>
</html>
