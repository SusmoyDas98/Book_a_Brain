<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobPostResponse;
use App\Models\Tutor;
use App\Models\TutorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function browse(Request $request)
    {
        $jobPosts = JobPost::where('status', 'Open')
            ->with('guardian')
            ->orderBy('created_at', 'desc')
            ->get();

        $appliedPostIds = JobPostResponse::where('tutor_id', Auth::id())
            ->pluck('job_post_id')->toArray();

        return view('job_posts.browse', compact('jobPosts', 'appliedPostIds'));
    }

    public function apply(Request $request, JobPost $jobPost)
    {
        if ($jobPost->status !== 'Open') {
            return redirect()->back()
                ->with('error', 'This job is no longer accepting applications.');
        }

        $existing = JobPostResponse::where('job_post_id', $jobPost->id)
            ->where('tutor_id', Auth::id())->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'You have already applied to this job.');
        }

        $validated = $request->validate([
            'application_message' => 'required|string|min:1|max:500',
        ]);

        $tutorProfile = TutorProfile::where('tutor_id', Auth::id())->first();

        if (!$tutorProfile) {
            return redirect()->back()
                ->with('error', 'Please complete your tutor profile before applying.');
        }

        $tutor = Tutor::where('tutor_id', Auth::id())->first();

        JobPostResponse::create([
            'job_post_id'                    => $jobPost->id,
            'guardian_id'                    => $jobPost->guardian_id,
            'tutor_id'                       => Auth::id(),
            'application_message'            => $validated['application_message'],
            'status'                         => 'Pending',
            'shortlisted'                    => false,
            'tutor_name'                     => $tutorProfile->name,
            'gender'                         => $tutorProfile->gender,
            'tutor_profile_pic'              => $tutorProfile->profile_picture,
            'cv'                             => $tutorProfile->cv,
            'tutor_educational_institutions' => $tutorProfile->educational_institutions,
            'tutor_work_experience'          => $tutorProfile->work_experience,
            'teaching_method'                => $tutorProfile->teaching_method,
            'availability'                   => $tutorProfile->availability,
            'preferred_mediums'              => $tutorProfile->preferred_mediums,
            'preferred_subjects'             => $tutorProfile->preferred_subjects,
            'preferred_classes'              => $tutorProfile->preferred_classes,
            'expected_salary'                => $tutorProfile->expected_salary,
            'tutor_rating'                   => $tutor ? $tutor->ratings : 0,
        ]);

        return redirect()->route('applications.index')
            ->with('success', 'Application submitted successfully!');
    }

    public function index()
    {
        $responses = JobPostResponse::where('tutor_id', Auth::id())
            ->with('jobPost')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('applications.index', compact('responses'));
    }

    public function withdraw(JobPostResponse $response)
    {
        if ((int) $response->tutor_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($response->status !== 'Pending') {
            return redirect()->back()
                ->with('error', 'You can only withdraw a pending application.');
        }

        if ($response->shortlisted) {
            $jobPost = JobPost::find($response->job_post_id);
            if ($jobPost && $jobPost->shortlisted_count > 0) {
                $jobPost->shortlisted_count -= 1;
                $jobPost->save();
            }
        }

        $response->delete();

        return redirect()->route('applications.index')
            ->with('success', 'Application withdrawn.');
    }
}
