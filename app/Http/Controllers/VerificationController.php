<?php

namespace App\Http\Controllers;

use App\Models\TutorProfile;
use App\Models\VerificationDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Admin approves a tutor's verification documents.
     * Sets tutor_profile verification_status to APPROVED.
     * Sets all PENDING verification docs to APPROVED.
     */
    public function approve(Request $request, $tutorId)
    {
        $this->ensureAdmin();

        $tutorProfile = TutorProfile::where('tutor_id', $tutorId)->firstOrFail();

        if ($tutorProfile->verification_status === 'APPROVED') {
            return back()->with('error', 'This tutor has already been verified.');
        }

        $tutorProfile->update([
            'verification_status' => 'APPROVED',
            'rejection_reason'    => null,
            'verified_at'         => now(),
        ]);

        VerificationDocument::where('tutor_id', $tutorId)
            ->where('status', 'PENDING')
            ->update([
                'status'      => 'APPROVED',
                'reviewed_by' => Auth::id(),
                'review_note' => $request->input('note'),
            ]);

        return back()->with('success', 'Tutor verified successfully.');
    }

    /**
     * Admin rejects a tutor's verification documents.
     * Sets tutor_profile verification_status to REJECTED.
     * Stores rejection reason (shown to tutor as notification later).
     */
    public function reject(Request $request, $tutorId)
    {
        $this->ensureAdmin();

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $tutorProfile = TutorProfile::where('tutor_id', $tutorId)->firstOrFail();

        $tutorProfile->update([
            'verification_status' => 'REJECTED',
            'rejection_reason'    => $request->reason,
            'verified_at'         => null,
        ]);

        VerificationDocument::where('tutor_id', $tutorId)
            ->where('status', 'PENDING')
            ->update([
                'status'      => 'REJECTED',
                'reviewed_by' => Auth::id(),
                'review_note' => $request->reason,
            ]);

        // TODO: Send notification to tutor when notification system is built
        // Notification::send($tutor, new DocumentRejectedNotification($request->reason));

        return back()->with('success', 'Tutor verification rejected. Tutor will be notified.');
    }

    /**
     * When tutor re-uploads documents after rejection,
     * reset verification_status back to PENDING.
     * Called from ProfileController after new doc upload.
     */
    public static function resetToPending(int $tutorId): void
    {
        TutorProfile::where('tutor_id', $tutorId)->update([
            'verification_status' => 'PENDING',
            'rejection_reason'    => null,
            'verified_at'         => null,
        ]);
    }

    private function ensureAdmin(): void
    {
        if (strtolower(Auth::user()->role) !== 'admin') {
            abort(403, 'Admin access required.');
        }
    }
}
