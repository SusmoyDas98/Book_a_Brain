<div style="background:white;border:2px solid #fde68a;border-radius:24px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.05);margin-bottom:1rem;">
    <p style="font-weight:800;color:#0f172a;font-size:1rem;margin-bottom:1rem;">
        <i class="bi bi-person-check me-2" style="color:#6366f1;"></i>Pending Hire Offer
    </p>

    @php
        $job = $confirmation->job;
    @endphp

    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:14px;padding:1rem;">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div style="flex:1">
                <p style="font-weight:700;color:#0f172a;font-size:0.95rem;margin:0 0 0.4rem;">
                    <i class="bi bi-briefcase me-1" style="color:#6366f1;"></i>
                    {{ $job->title ?? 'Tuition Job' }}
                </p>
                <div class="d-flex flex-wrap gap-3 mb-2" style="font-size:0.82rem;color:#64748b;">
                    <span><i class="bi bi-book me-1"></i>{{ $job->subject ?? '—' }}</span>
                    <span><i class="bi bi-geo-alt me-1"></i>{{ $job->location ?? '—' }}</span>
                    <span><i class="bi bi-currency-dollar me-1"></i>৳{{ $job ? number_format($job->expected_salary) : '—' }}/month</span>
                </div>
                <p style="font-size:0.78rem;color:#94a3b8;margin:0;">
                    Offer received: {{ $confirmation->created_at->format('d M Y, h:i A') }}
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <form method="POST" action="{{ route('tutor.hire.confirm', $confirmation->id) }}">
                    @csrf
                    <button type="submit"
                            style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;font-weight:700;border:none;border-radius:12px;padding:0.6rem 1.25rem;font-size:0.85rem;cursor:pointer;box-shadow:0 4px 12px rgba(99,102,241,0.3);">
                        <i class="bi bi-check-circle me-1"></i>Confirm engagement
                    </button>
                </form>
                <form method="POST" action="{{ route('tutor.hire.decline', $confirmation->id) }}">
                    @csrf
                    <button type="submit"
                            style="background:#f1f5f9;color:#ef4444;font-weight:700;border:2px solid #fecaca;border-radius:12px;padding:0.6rem 1.25rem;font-size:0.85rem;cursor:pointer;"
                            onclick="return confirm('Are you sure you want to decline this hire offer?')">
                        <i class="bi bi-x-circle me-1"></i>Decline
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
