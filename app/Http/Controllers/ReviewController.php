<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use App\Models\TuitionContract;
use App\Models\Tutor;
use App\Services\TrustScoreService;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Show the review form for a contract.
     */
    public function create(TuitionContract $contract)
    {
        $user = Auth::user();

        // Only guardians can review
        if ($contract->guardian_id !== $user->id) {
            abort(403, 'Only the guardian on this contract can leave a review.');
        }

        // Contract must be ACTIVE or ENDED
        if (! in_array($contract->status, ['ACTIVE', 'ENDED'])) {
            return back()->with('error', 'Reviews can only be left for active or ended contracts.');
        }

        $contract->load('tutor');

        $existingReview = Review::where('contract_id', $contract->id)
            ->where('guardian_id', $user->id)
            ->first();

        return view('reviews.create', compact('contract', 'existingReview'));
    }

    /**
     * Store a new review (duplicates allowed).
     */
    public function store(StoreReviewRequest $request, TuitionContract $contract)
    {
        $user = Auth::user();

        if ($contract->guardian_id !== $user->id) {
            abort(403);
        }

        if (! in_array($contract->status, ['ACTIVE', 'ENDED'])) {
            return back()->with('error', 'Reviews can only be left for active or ended contracts.');
        }

        // Create or update the review
        Review::updateOrCreate(
            [
                'guardian_id' => $user->id,
                'contract_id' => $contract->id,
            ],
            [
                'tutor_id' => $contract->tutor_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        // Recalculate tutor's average rating
        $avgRating = Review::where('tutor_id', $contract->tutor_id)->avg('rating');
        Tutor::where('tutor_id', $contract->tutor_id)->update([
            'ratings' => round($avgRating, 2),
        ]);

        // Recalculate trust score
        app(TrustScoreService::class)->recalculate($contract->tutor_id);

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Thank you! Your review has been submitted.');
    }
}
