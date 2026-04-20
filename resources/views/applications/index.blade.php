<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Applications | Book-a-Brain</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<link rel="stylesheet" href="{{ asset('css/job_posts.css') }}">
<link rel="stylesheet" href="{{ asset('css/browse_jobs.css') }}">

</head>

<body>

<x-navbar/>

<div class="container applications-list">

  <div class="page-header">
    <h1 class="page-title">My Applications</h1>
    <a href="{{ route('jobs.browse') }}"
       class="btn-primary-custom">
      <i class="bi bi-search me-2"></i>Browse Jobs
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

  @if($responses->isEmpty())
    <div class="empty-state">
      <i class="bi bi-inbox"></i>
      <h5>No applications yet</h5>
      <p>You haven't applied to any jobs yet.</p>
      <a href="{{ route('jobs.browse') }}" class="btn-primary-custom mt-3 d-inline-block">
        Browse Available Jobs
      </a>
    </div>
  @else
    @foreach($responses as $response)
    <div class="app-card">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div style="flex:1">
          <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
            <h5 class="fw-bold mb-0">
              {{ $response->jobPost->title ?? 'Job Removed' }}
            </h5>
            <span class="status-badge
              @if($response->status === 'Pending') badge-closed
              @elseif($response->status === 'Shortlisted') badge-shortlisting
              @elseif($response->status === 'Rejected') badge-rejected
              @elseif($response->status === 'Hired') badge-hired
              @endif">
              {{ $response->status }}
            </span>
          </div>

          @if($response->jobPost)
          <div class="text-secondary small mb-2">
            {{ $response->jobPost->subject }} &nbsp;·&nbsp;
            {{ $response->jobPost->class_level }} &nbsp;·&nbsp;
            <i class="bi bi-geo-alt me-1"></i>{{ $response->jobPost->location }} &nbsp;·&nbsp;
            ৳{{ number_format($response->jobPost->expected_salary) }}/month
          </div>
          <div class="mb-2">
            <span class="text-secondary small">Job Status: </span>
            <span class="status-badge badge-{{ strtolower($response->jobPost->status ?? 'closed') }}">
              {{ $response->jobPost->status ?? 'Removed' }}
            </span>
          </div>
          @endif

          <div class="p-2 rounded-3 small text-secondary"
               style="background:#f1f5f9;border-left:3px solid #94a3b8">
            "{{ \Illuminate\Support\Str::limit($response->application_message, 100) }}"
          </div>
          <div class="text-secondary small mt-2">
            <i class="bi bi-calendar3 me-1"></i>Applied: {{ $response->created_at->format('d M Y') }}
          </div>
        </div>

        <div>
          @if($response->status === 'Pending')
          <form method="POST"
                action="{{ route('applications.withdraw', $response) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger-custom"
              onclick="return confirm('Are you sure you want to withdraw this application?')">
              <i class="bi bi-x-lg me-1"></i>Withdraw
            </button>
          </form>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  @endif

  @if($responses->hasPages())
    <div class="d-flex justify-content-center mt-4">
      {{ $responses->links() }}
    </div>
  @endif

</div>

</body>
</html>
