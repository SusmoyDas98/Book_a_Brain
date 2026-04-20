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
  @if(session('info'))
    <div style="background:#eff6ff;border:2px solid #bfdbfe;color:#3b82f6;border-radius:14px;padding:0.9rem 1.25rem;margin-bottom:1.25rem;font-weight:600;font-size:0.88rem;">
      <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
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

    {{-- Cancellation section for active engagements --}}
    @php $hireConfirmation = $jobPost->hireConfirmation; @endphp
    @if($hireConfirmation)
      @if($hireConfirmation->status === 'both_confirmed')
        <div style="margin-top:1.5rem;background:#fef2f2;border:2px solid #fecaca;border-radius:16px;padding:1.25rem;">
          <p style="font-weight:700;color:#ef4444;margin-bottom:0.75rem;">
            <i class="bi bi-x-circle me-2"></i>Request Cancellation
          </p>
          <form method="POST" action="{{ route('guardian.hire.cancel', $hireConfirmation->id) }}">
            @csrf
            <div style="margin-bottom:8px;">
              <label class="bab-label">Reason for cancellation</label>
              <textarea name="reason" required minlength="10" maxlength="500"
                        class="bab-input"
                        placeholder="Please explain why you need to cancel..."
                        rows="3"></textarea>
            </div>
            <button type="submit"
                    style="background:#ef4444;color:white;font-weight:700;border:none;border-radius:12px;padding:0.65rem 1.5rem;cursor:pointer;"
                    onclick="return confirm('This will submit a cancellation request to the admin for approval. Continue?')">
              <i class="bi bi-x-circle me-2"></i>Request cancellation
            </button>
          </form>
        </div>
      @elseif($hireConfirmation->status === 'cancellation_requested')
        <div style="margin-top:1.5rem;background:#fffbeb;border:2px solid #fde68a;border-radius:16px;padding:1rem;">
          <p style="color:#d97706;font-weight:600;margin:0;">
            <i class="bi bi-hourglass-split me-2"></i>Cancellation request submitted. Awaiting admin approval.
          </p>
        </div>
      @elseif($hireConfirmation->status === 'cancelled')
        <div style="margin-top:1.5rem;">
          <span style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:999px;font-size:0.78rem;font-weight:700;padding:4px 14px;">
            <i class="bi bi-x-circle me-1"></i>Engagement cancelled
          </span>
        </div>
      @endif
    @endif
  </div>

  {{-- Applicants Section --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
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

          {{-- Hire button for shortlisted applicants --}}
          @if($response->status === 'Shortlisted' && in_array($jobPost->status, ['Open','Shortlisting']))
          <form method="POST" action="{{ route('guardian.hire', $response->id) }}"
                onsubmit="return confirm('Are you sure you want to hire this tutor? All other shortlisted applicants will be discarded.')">
            @csrf
            <button type="submit"
                    style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border:none;border-radius:10px;padding:7px 14px;font-size:0.82rem;cursor:pointer;width:100%;">
              <i class="bi bi-person-check me-1"></i>Hire this tutor
            </button>
          </form>
          @endif

          {{-- Status badges when job is already hired/online/cancelled --}}
          @if($response->status === 'Hired' || $jobPost->status === 'Hired')
            <span style="background:rgba(245,158,11,0.1);color:#d97706;border-radius:999px;font-size:0.72rem;font-weight:700;padding:3px 12px;text-align:center;">
              Awaiting tutor confirmation
            </span>
          @endif
          @if($jobPost->status === 'Online')
            <span style="background:rgba(34,197,94,0.1);color:#16a34a;border-radius:999px;font-size:0.72rem;font-weight:700;padding:3px 12px;text-align:center;">
              Engagement active
            </span>
          @endif
          @if($jobPost->status === 'Cancelled')
            <span style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:999px;font-size:0.72rem;font-weight:700;padding:3px 12px;text-align:center;">
              Cancelled
            </span>
          @endif

          @if($response->status === 'Pending' && !$response->shortlisted && $jobPost->shortlisted_count < 5 && in_array($jobPost->status, ['Open','Shortlisting']))
          <form method="POST" action="{{ route('job_posts.shortlist', $jobPost) }}">
            @csrf
            <input type="hidden" name="response_id" value="{{ $response->id }}">
            <button type="submit" class="btn-success-custom w-100">
              <i class="bi bi-check-circle me-1"></i>Shortlist
            </button>
          </form>
          @endif
          @if($response->shortlisted && in_array($jobPost->status, ['Open','Shortlisting']))
          <form method="POST" action="{{ route('job_posts.remove_shortlist', $jobPost) }}">
            @csrf
            <input type="hidden" name="response_id" value="{{ $response->id }}">
            <button type="submit" class="btn-warning-custom w-100">
              <i class="bi bi-x-circle me-1"></i>Remove
            </button>
          </form>
          @endif
          @if($response->status === 'Pending' && !$response->shortlisted && in_array($jobPost->status, ['Open','Shortlisting']))
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
