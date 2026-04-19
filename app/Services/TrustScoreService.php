<?php

namespace App\Services;

use App\Models\Review;
use App\Models\TuitionContract;
use App\Models\TutorProfile;

class TrustScoreService
{
    /**
     * Calculate and return the trust score (0-100) for a tutor.
     *
     * Weight allocation:
     * 1. Average Rating (30%) — from reviews table
     * 2. Verification Status (20%) — APPROVED=20, PENDING=5, REJECTED=0
     * 3. Profile Completeness (15%) — based on filled fields
     * 4. Successful Hires (20%) — completed contracts (ENDED)
     * 5. Response Behavior (15%) — ratio of accepted vs total contracts
     */
    public function calculate(int $tutorId): float
    {
        $profile = TutorProfile::where('tutor_id', $tutorId)->first();

        if (! $profile) {
            return 0;
        }

        // 1. Average Rating (30 points max) — scale 1-5 to 0-30
        $avgRating = Review::where('tutor_id', $tutorId)->avg('rating') ?? 0;
        $ratingScore = ($avgRating / 5) * 30;

        // 2. Verification Status (20 points max)
        $verificationScore = match ($profile->verification_status) {
            'APPROVED' => 20,
            'PENDING' => 5,
            default => 0,
        };

        // 3. Profile Completeness (15 points max)
        $fields = [
            $profile->contact_no,
            $profile->gender,
            $profile->profile_picture,
            $profile->cv,
            $profile->educational_institutions,
            $profile->work_experience,
            $profile->teaching_method,
            $profile->availability,
            $profile->preferred_mediums,
            $profile->preferred_subjects,
            $profile->expected_salary,
        ];
        $filledCount = collect($fields)->filter(fn ($v) => ! is_null($v) && $v !== '')->count();
        $completionScore = ($filledCount / count($fields)) * 15;

        // 4. Successful Hires (20 points max) — cap at 10 completed contracts
        $completedContracts = TuitionContract::where('tutor_id', $tutorId)
            ->where('status', 'ENDED')
            ->count();
        $hiresScore = min($completedContracts / 10, 1) * 20;

        // 5. Response Behavior (15 points max) — accepted / (accepted + declined)
        $totalContracts = TuitionContract::where('tutor_id', $tutorId)->count();
        $activeOrEnded = TuitionContract::where('tutor_id', $tutorId)
            ->whereIn('status', ['ACTIVE', 'ENDED'])
            ->count();

        if ($totalContracts > 0) {
            $responseScore = ($activeOrEnded / $totalContracts) * 15;
        } else {
            $responseScore = 7.5; // Neutral for new tutors
        }

        return round($ratingScore + $verificationScore + $completionScore + $hiresScore + $responseScore, 2);
    }

    /**
     * Recalculate and persist the trust score for a tutor.
     */
    public function recalculate(int $tutorId): void
    {
        $score = $this->calculate($tutorId);

        TutorProfile::where('tutor_id', $tutorId)->update([
            'trust_score' => $score,
            'trust_score_updated_at' => now(),
        ]);
    }
}
