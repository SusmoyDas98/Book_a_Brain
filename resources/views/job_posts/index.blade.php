<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Job Posts | Book-a-Brain</title>

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

  <div class="page-header">
    <h1 class="page-title">My Job Posts</h1>
    <a href="{{ route('job_posts.create') }}" class="btn-primary-custom">
      <i class="bi bi-plus-lg me-2"></i>Post a New Job
    </a>
  </div>

  @if($jobPosts->isEmpty())
    <div class="empty-state">
      <i class="bi bi-briefcase"></i>
      <h4>No job posts yet</h4>
      <p>You haven't posted any tuition jobs yet.</p>
      <a href="{{ route('job_posts.create') }}" class="btn-primary-custom mt-3 d-inline-block">
        Post Your First Job
      </a>
    </div>
  @else
    @foreach($jobPosts as $post)
    <div class="job-card">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
          <h4 class="fw-bold mb-1">{{ $post->title }}</h4>
          <p class="text-secondary mb-2" style="font-size:0.9rem">
            {{ $post->subject }} &nbsp;·&nbsp; {{ $post->class_level }}
          </p>
          <span class="status-badge badge-{{ strtolower($post->status) }}">
            {{ $post->status }}
          </span>
        </div>
        <div class="text-end">
          <div class="fw-bold text-primary fs-5">৳{{ number_format($post->expected_salary) }}/month</div>
          <div class="text-secondary small mt-1">
            <i class="bi bi-geo-alt me-1"></i>{{ $post->location }}
          </div>
        </div>
      </div>

      <div class="row mt-3 g-2" style="font-size:0.85rem; color:#475569">
        <div class="col-auto">
          <i class="bi bi-translate me-1"></i>{{ $post->medium }}
        </div>
        <div class="col-auto">
          <i class="bi bi-laptop me-1"></i>{{ $post->mode }}
        </div>
        <div class="col-auto">
          <i class="bi bi-people me-1"></i>Shortlisted: {{ $post->shortlisted_count }}/5
        </div>
        <div class="col-auto">
          <i class="bi bi-calendar3 me-1"></i>{{ $post->created_at->format('d M Y') }}
        </div>
      </div>

      <div class="d-flex gap-2 mt-3 flex-wrap">
        <a href="{{ route('job_posts.show', $post) }}"
           class="btn btn-outline-dark btn-sm" style="border-radius:10px;font-weight:600">
          <i class="bi bi-people me-1"></i>View Applicants
        </a>
        @if(in_array($post->status, ['Open','Shortlisting']))
        <a href="{{ route('job_posts.edit', $post) }}"
           class="btn btn-outline-primary btn-sm" style="border-radius:10px;font-weight:600">
          <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @endif
        @if($post->status === 'Open')
        <form method="POST" action="{{ route('job_posts.destroy', $post) }}" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit"
            class="btn btn-outline-danger btn-sm" style="border-radius:10px;font-weight:600"
            onclick="return confirm('Are you sure you want to delete this post?')">
            <i class="bi bi-trash me-1"></i>Delete
          </button>
        </form>
        @endif
      </div>
    </div>
    @endforeach
  @endif

</div>

</body>
</html>
