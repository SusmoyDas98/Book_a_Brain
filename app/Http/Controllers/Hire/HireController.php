<?php

namespace App\Http\Controllers\Hire;

use App\Http\Controllers\Controller;
use App\Models\HireConfirmation;
use App\Models\JobPost;
use App\Models\JobPostResponse;
use App\Services\HireNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HireController extends Controller
{
    public function hire(Request $request, $applicationId)
    {
        $guardian = Auth::user()->guardian;
        if (! $guardian) {
            abort(403);
        }

        $application = JobPostResponse::findOrFail($applicationId);
        $job = JobPost::findOrFail($application->job_post_id);

        if ((int) $job->guardian_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($application->status !== JobPostResponse::STATUS_SHORTLISTED) {
            return back()->with('error', 'This applicant cannot be hired at this stage.');
        }

        if (in_array($job->status, [JobPost::STATUS_HIRED, JobPost::STATUS_ONLINE])) {
            return back()->with('error', 'This job already has an active hire.');
        }

        DB::transaction(function () use ($application, $job, $guardian) {
            $application->update(['status' => JobPostResponse::STATUS_HIRED]);

            $discardedTutorIds = JobPostResponse::where('job_post_id', $job->id)
                ->where('id', '!=', $application->id)
                ->whereIn('status', [JobPostResponse::STATUS_SHORTLISTED])
                ->pluck('tutor_id')
                ->toArray();

            JobPostResponse::where('job_post_id', $job->id)
                ->where('id', '!=', $application->id)
                ->whereIn('status', [JobPostResponse::STATUS_SHORTLISTED])
                ->update(['status' => JobPostResponse::STATUS_DISCARDED]);

            $job->update(['status' => JobPost::STATUS_HIRED]);

            HireConfirmation::create([
                'job_id' => $job->id,
                'application_id' => $application->id,
                'guardian_id' => $guardian->id,
                'tutor_id' => $application->tutor_id,
                'guardian_confirmed' => true,
                'guardian_confirmed_at' => now(),
                'tutor_confirmed' => false,
                'status' => 'awaiting_tutor',
            ]);

            HireNotificationService::logEvent([
                'event_type' => 'guardian_hired',
                'job_id' => $job->id,
                'application_id' => $application->id,
                'guardian_id' => $guardian->id,
                'tutor_id' => $application->tutor_id,
                'performed_by' => Auth::id(),
                'performed_role' => 'guardian',
                'payment_status' => 'pending',
            ]);

            HireNotificationService::notifyTutor(
                $application->tutor_id,
                'You have been hired!',
                'A guardian has selected you for a tuition job. Please confirm to activate the engagement.',
                'hire_offer',
                $job->id
            );

            foreach ($discardedTutorIds as $tutorId) {
                HireNotificationService::notifyTutor(
                    $tutorId,
                    'Application unsuccessful',
                    'The guardian has selected another tutor for this job. Thank you for applying.',
                    'tutor_discarded',
                    $job->id
                );
            }
        });

        return redirect()->route('dashboard')
            ->with('success', 'Tutor hired successfully. Waiting for tutor confirmation.');
    }

    public function confirmHire(Request $request, $hireConfirmationId)
    {
        $tutor = Auth::user()->tutor;
        if (! $tutor) {
            abort(403);
        }

        $hireConfirmation = HireConfirmation::findOrFail($hireConfirmationId);

        if ((int) $hireConfirmation->tutor_id !== (int) $tutor->tutor_id) {
            abort(403);
        }

        if ($hireConfirmation->status !== 'awaiting_tutor') {
            return back()->with('error', 'Already confirmed.');
        }

        DB::transaction(function () use ($hireConfirmation, $tutor) {
            $hireConfirmation->update([
                'tutor_confirmed' => true,
                'tutor_confirmed_at' => now(),
                'status' => 'both_confirmed',
            ]);

            JobPostResponse::where('id', $hireConfirmation->application_id)
                ->update(['status' => JobPostResponse::STATUS_CONFIRMED]);

            JobPost::where('id', $hireConfirmation->job_id)
                ->update(['status' => JobPost::STATUS_ONLINE]);

            HireNotificationService::logEvent([
                'event_type' => 'tutor_confirmed',
                'job_id' => $hireConfirmation->job_id,
                'application_id' => $hireConfirmation->application_id,
                'guardian_id' => $hireConfirmation->guardian_id,
                'tutor_id' => $tutor->tutor_id,
                'performed_by' => Auth::id(),
                'performed_role' => 'tutor',
            ]);

            HireNotificationService::logEvent([
                'event_type' => 'job_online',
                'job_id' => $hireConfirmation->job_id,
                'application_id' => $hireConfirmation->application_id,
                'guardian_id' => $hireConfirmation->guardian_id,
                'tutor_id' => $tutor->tutor_id,
                'performed_by' => Auth::id(),
                'performed_role' => 'tutor',
            ]);

            HireNotificationService::notifyGuardian(
                $hireConfirmation->guardian_id,
                'Job is now online!',
                'The tutor has confirmed the hire. Your tutoring engagement is now active.',
                'job_online',
                $hireConfirmation->job_id
            );

            HireNotificationService::notifyTutor(
                $tutor->tutor_id,
                'Engagement confirmed!',
                'You have confirmed the hire. Your tutoring engagement is now active.',
                'job_online',
                $hireConfirmation->job_id
            );

            HireNotificationService::notifyAdmin(
                'Job is now online',
                'A tuition engagement has been confirmed and is now active.',
                'job_online',
                $hireConfirmation->job_id
            );
        });

        return redirect()->route('dashboard')
            ->with('success', 'You have confirmed the hire. The engagement is now active!');
    }

    public function declineHire(Request $request, $hireConfirmationId)
    {
        $tutor = Auth::user()->tutor;
        if (! $tutor) {
            abort(403);
        }

        $hireConfirmation = HireConfirmation::findOrFail($hireConfirmationId);

        if ((int) $hireConfirmation->tutor_id !== (int) $tutor->tutor_id) {
            abort(403);
        }

        if ($hireConfirmation->status !== 'awaiting_tutor') {
            return back()->with('error', 'This hire offer cannot be declined at this stage.');
        }

        DB::transaction(function () use ($hireConfirmation, $tutor) {
            $hireConfirmation->update(['status' => 'declined']);

            JobPostResponse::where('id', $hireConfirmation->application_id)
                ->update(['status' => JobPostResponse::STATUS_DECLINED]);

            $hasOtherShortlisted = JobPostResponse::where('job_post_id', $hireConfirmation->job_id)
                ->where('status', JobPostResponse::STATUS_SHORTLISTED)
                ->exists();

            $newJobStatus = $hasOtherShortlisted
                ? JobPost::STATUS_SHORTLISTING
                : JobPost::STATUS_OPEN;

            JobPost::where('id', $hireConfirmation->job_id)
                ->update(['status' => $newJobStatus]);

            HireNotificationService::logEvent([
                'event_type' => 'tutor_declined',
                'job_id' => $hireConfirmation->job_id,
                'application_id' => $hireConfirmation->application_id,
                'guardian_id' => $hireConfirmation->guardian_id,
                'tutor_id' => $tutor->tutor_id,
                'performed_by' => Auth::id(),
                'performed_role' => 'tutor',
            ]);

            HireNotificationService::notifyGuardian(
                $hireConfirmation->guardian_id,
                'Tutor declined the hire offer',
                'The tutor has declined the hire offer. Please review your shortlist or reopen the job to new applications.',
                'tutor_declined',
                $hireConfirmation->job_id
            );
        });

        return redirect()->route('dashboard')
            ->with('info', 'You have declined the hire offer.');
    }

    public function requestCancellation(Request $request, $hireConfirmationId)
    {
        $guardian = Auth::user()->guardian;
        if (! $guardian) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);

        $hireConfirmation = HireConfirmation::findOrFail($hireConfirmationId);

        if ((int) $hireConfirmation->guardian_id !== (int) $guardian->id) {
            abort(403);
        }

        if ($hireConfirmation->status !== 'both_confirmed') {
            return back()->with('error', 'Cancellation can only be requested for active engagements.');
        }

        DB::transaction(function () use ($hireConfirmation, $guardian, $request) {
            $hireConfirmation->update([
                'status' => 'cancellation_requested',
                'cancellation_reason' => $request->reason,
            ]);

            HireNotificationService::logEvent([
                'event_type' => 'cancellation_requested',
                'job_id' => $hireConfirmation->job_id,
                'application_id' => $hireConfirmation->application_id,
                'guardian_id' => $guardian->id,
                'tutor_id' => $hireConfirmation->tutor_id,
                'performed_by' => Auth::id(),
                'performed_role' => 'guardian',
            ]);

            HireNotificationService::notifyAdmin(
                'Cancellation request submitted',
                'A guardian has requested cancellation of an active engagement. Admin review and approval is required.',
                'cancellation_requested',
                $hireConfirmation->job_id
            );

            HireNotificationService::notifyTutor(
                $hireConfirmation->tutor_id,
                'Cancellation request submitted',
                'The guardian has requested to cancel this engagement. An admin will review the request.',
                'cancellation_requested',
                $hireConfirmation->job_id
            );
        });

        return redirect()->route('dashboard')
            ->with('info', 'Cancellation request submitted. Awaiting admin approval.');
    }

    public function approveCancellation(Request $request, $hireConfirmationId)
    {
        $hireConfirmation = HireConfirmation::findOrFail($hireConfirmationId);

        if ($hireConfirmation->status !== 'cancellation_requested') {
            return back()->with('error', 'This cancellation request has already been processed.');
        }

        DB::transaction(function () use ($hireConfirmation) {
            $hireConfirmation->update([
                'status' => 'cancelled',
                'cancelled_by' => Auth::id(),
                'cancelled_at' => now(),
            ]);

            JobPostResponse::where('id', $hireConfirmation->application_id)
                ->update(['status' => JobPostResponse::STATUS_REJECTED]);

            JobPost::where('id', $hireConfirmation->job_id)
                ->update(['status' => JobPost::STATUS_CANCELLED]);

            HireNotificationService::logEvent([
                'event_type' => 'job_cancelled',
                'job_id' => $hireConfirmation->job_id,
                'application_id' => $hireConfirmation->application_id,
                'guardian_id' => $hireConfirmation->guardian_id,
                'tutor_id' => $hireConfirmation->tutor_id,
                'performed_by' => Auth::id(),
                'performed_role' => 'admin',
            ]);

            HireNotificationService::notifyGuardian(
                $hireConfirmation->guardian_id,
                'Cancellation approved',
                'Your cancellation request has been approved by the admin. The engagement has been closed.',
                'cancellation_approved',
                $hireConfirmation->job_id
            );

            HireNotificationService::notifyTutor(
                $hireConfirmation->tutor_id,
                'Engagement cancelled',
                "The guardian's cancellation request has been approved. The engagement has been closed.",
                'cancellation_approved',
                $hireConfirmation->job_id
            );
        });

        return redirect()->route('dashboard')
            ->with('success', 'Cancellation approved.');
    }
}
