<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Job Post | Book-a-Brain</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<link rel="stylesheet" href="{{ asset('css/job_posts.css') }}">

</head>

<body>

<x-navbar/>

<div class="container page-wrapper">
  <div class="page-header">
    <h1 class="page-title">Edit Job Post</h1>
    <a href="{{ route('job_posts.show', $jobPost) }}"
       class="btn btn-outline-secondary btn-sm" style="border-radius:10px">
      <i class="bi bi-arrow-left me-1"></i>Back
    </a>
  </div>

  @if(session('error'))
    <div class="alert-danger-custom">
      <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    </div>
  @endif

  <div class="form-card">
    <div class="d-flex align-items-center gap-2 mb-4">
      <span style="font-size:0.9rem;color:#64748b">Current Status:</span>
      <span class="status-badge badge-{{ strtolower($jobPost->status) }}">
        {{ $jobPost->status }}
      </span>
    </div>

    <form method="POST" action="{{ route('job_posts.update', $jobPost) }}">
      @csrf
      @method('PUT')

      <div class="mb-4">
        <label class="form-label-custom">JOB TITLE</label>
        <input type="text" name="title" value="{{ old('title', $jobPost->title) }}"
          class="form-control-custom"
          placeholder="e.g. Need English tutor for my son">
        @error('title')
          <div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-4">
        <label class="form-label-custom">SUBJECT</label>
        <input type="text" name="subject" value="{{ old('subject', $jobPost->subject) }}"
          class="form-control-custom"
          placeholder="e.g. English, Math, Physics">
        @error('subject')
          <div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-4">
        <label class="form-label-custom">CLASS / LEVEL</label>
        <input type="text" name="class_level" value="{{ old('class_level', $jobPost->class_level) }}"
          class="form-control-custom"
          placeholder="e.g. Class 9, HSC, O Level, A Level">
        @error('class_level')
          <div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-4">
        <label class="form-label-custom">MONTHLY SALARY (BDT)</label>
        <input type="number" name="expected_salary" min="0" step="100"
          value="{{ old('expected_salary', $jobPost->expected_salary) }}"
          class="form-control-custom"
          placeholder="e.g. 5000">
        @error('expected_salary')
          <div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-4">
        <label class="form-label-custom">LOCATION</label>
        <input type="text" name="location" value="{{ old('location', $jobPost->location) }}"
          class="form-control-custom"
          placeholder="e.g. Dhanmondi, Dhaka">
        @error('location')
          <div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>
        @enderror
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label-custom">MEDIUM OF INSTRUCTION</label>
          <select name="medium" class="form-control-custom">
            <option value="">Select Medium</option>
            <option value="Bangla" {{ old('medium', $jobPost->medium)==='Bangla'?'selected':'' }}>Bangla</option>
            <option value="English" {{ old('medium', $jobPost->medium)==='English'?'selected':'' }}>English</option>
            <option value="Both" {{ old('medium', $jobPost->medium)==='Both'?'selected':'' }}>Both</option>
          </select>
          @error('medium')
            <div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label-custom">MODE OF TEACHING</label>
          <select name="mode" class="form-control-custom">
            <option value="">Select Mode</option>
            <option value="Online" {{ old('mode', $jobPost->mode)==='Online'?'selected':'' }}>Online</option>
            <option value="Offline" {{ old('mode', $jobPost->mode)==='Offline'?'selected':'' }}>Offline</option>
            <option value="Both" {{ old('mode', $jobPost->mode)==='Both'?'selected':'' }}>Both</option>
          </select>
          @error('mode')
            <div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label-custom">ADDITIONAL DETAILS (OPTIONAL)</label>
        <textarea name="description" rows="4"
          class="form-control-custom"
          placeholder="Any specific requirements, timing preferences, etc.">{{ old('description', $jobPost->description) }}</textarea>
        @error('description')
          <div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>
        @enderror
      </div>

      <div class="d-flex gap-3">
        <button type="submit" class="btn-primary-custom">
          <i class="bi bi-check2 me-2"></i>Update Job Post
        </button>
        <a href="{{ route('job_posts.show', $jobPost) }}"
           class="btn btn-outline-secondary" style="border-radius:12px">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
