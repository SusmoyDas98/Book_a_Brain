@extends('layouts.app')

@section('content')

<div style="min-height:100vh; padding: 2.5rem 0 5rem; margin-top:40px;">
<div class="container" style="max-width:600px;">

    {{-- HEADER --}}
    <div class="mb-4">
        <a href="{{ route('contracts.show', $contract) }}" style="color:#6366f1;font-weight:600;font-size:0.85rem;text-decoration:none;">
            <i class="bi bi-arrow-left me-1"></i>Back to Contract
        </a>
    </div>

    <div style="background:white;border:2px solid #e2e8f0;border-radius:24px;padding:2rem;box-shadow:0 8px 30px rgba(99,102,241,0.07);">

        {{-- Title --}}
        <div style="text-align:center;margin-bottom:1.75rem;">
            <div style="width:56px;height:56px;background:rgba(245,158,11,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="bi bi-star-fill" style="color:#f59e0b;font-size:1.5rem;"></i>
            </div>
            <h2 style="color:#0f172a;font-weight:800;margin-bottom:0.3rem;">Rate Your Tutor</h2>
            <p style="color:#64748b;font-size:0.88rem;margin:0;">
                How was your experience with <strong style="color:#0f172a;">{{ $contract->tutor->name }}</strong>?
            </p>
            <p style="color:#94a3b8;font-size:0.78rem;margin:0.3rem 0 0;">
                <i class="bi bi-book me-1"></i>{{ $contract->subject }} · ৳{{ number_format($contract->salary) }}/mo
            </p>
        </div>

        <form action="{{ route('reviews.store', $contract) }}" method="POST">
            @csrf

            {{-- Star Rating --}}
            <div style="text-align:center;margin-bottom:1.5rem;">
                <label class="bab-label" style="text-align:center;">Your Rating</label>
                <div id="star-rating" style="display:flex;justify-content:center;gap:8px;margin-top:0.5rem;">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="star-btn" data-value="{{ $i }}"
                                style="background:none;border:none;cursor:pointer;font-size:2.2rem;color:#cbd5e1;transition:all 0.2s;padding:0 2px;"
                                onmouseover="highlightStars({{ $i }})"
                                onmouseout="resetStars()"
                                onclick="selectStar({{ $i }})">
                            <i class="bi bi-star-fill"></i>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input" value="">
                <p id="rating-text" style="color:#94a3b8;font-size:0.78rem;margin:0.5rem 0 0;">Click a star to rate</p>
                @error('rating')
                    <p style="color:#ef4444;font-size:0.78rem;margin:0.3rem 0 0;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Comment --}}
            <div style="margin-bottom:1.5rem;">
                <label class="bab-label">Your Feedback (optional)</label>
                <textarea name="comment" rows="4" class="bab-input"
                          placeholder="Tell us about your experience — this helps other guardians make informed decisions..."
                          style="resize:vertical;">{{ old('comment', $existingReview->comment ?? '') }}</textarea>
                @error('comment')
                    <p style="color:#ef4444;font-size:0.78rem;margin:0.3rem 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bab-btn-primary" style="width:100%;text-align:center;">
                <i class="bi bi-send me-2"></i>Submit Review
            </button>
        </form>
    </div>

</div>
</div>

<script>
    let selectedRating = 0;
    const ratingLabels = ['', 'Poor', 'Below Average', 'Good', 'Very Good', 'Excellent'];
    const ratingColors = ['', '#ef4444', '#f97316', '#eab308', '#22c55e', '#6366f1'];

    function highlightStars(n) {
        document.querySelectorAll('.star-btn').forEach((btn, i) => {
            btn.style.color = i < n ? '#f59e0b' : '#cbd5e1';
            btn.style.transform = i < n ? 'scale(1.15)' : 'scale(1)';
        });
    }

    function resetStars() {
        highlightStars(selectedRating);
    }

    function selectStar(n) {
        selectedRating = n;
        document.getElementById('rating-input').value = n;
        highlightStars(n);
        const text = document.getElementById('rating-text');
        text.textContent = `${n}/5 — ${ratingLabels[n]}`;
        text.style.color = ratingColors[n];
        text.style.fontWeight = '700';
    }

    @if(old('rating', $existingReview->rating ?? 0) > 0)
        selectStar({{ old('rating', $existingReview->rating) }});
    @endif
</script>

@endsection
