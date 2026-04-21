<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\JobPostResponse;
use App\Models\Subscription;
use App\Models\TutorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobPostController extends Controller
{
    public function index()
    {
        $jobPosts = JobPost::where('guardian_id', Auth::id())
            ->orderBy('created_at', 'desc')->get();

        return view('job_posts.index', compact('jobPosts'));
    }

    public function create()
    {
        return view('job_posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'class_level' => 'required|string|max:100',
            'expected_salary' => 'required|numeric|min:1',
            'location' => 'required|string|max:255',
            'medium' => 'required|in:Bangla,English,Both',
            'mode' => 'required|in:Online,Offline,Both',
            'description' => 'nullable|string|max:1000',
        ]);

        $duplicate = JobPost::where('guardian_id', Auth::id())
            ->where('subject', $validated['subject'])
            ->where('status', 'Open')
            ->exists();
        if ($duplicate) {
            session()->flash('warning', 'You already have an open job for this subject.');
        }

        JobPost::create(array_merge($validated, [
            'guardian_id' => Auth::id(),
            'status' => 'Open',
            'shortlisted_count' => 0,
        ]));

        return redirect()->route('job_posts.index')
            ->with('success', 'Job post created successfully!');
    }

    public function show(JobPost $jobPost)
    {
        if ((int) $jobPost->guardian_id !== (int) Auth::id()) {
            abort(403);
        }

        $responses = JobPostResponse::where('job_post_id', $jobPost->id)->get();
        $profiles = [];
        foreach ($responses as $response) {
            $profiles[$response->tutor_id] = TutorProfile::where('tutor_id', $response->tutor_id)->first();
        }

        return view('job_posts.show', compact('jobPost', 'responses', 'profiles'));
    }

    public function edit(JobPost $jobPost)
    {
        if ((int) $jobPost->guardian_id !== (int) Auth::id()) {
            abort(403);
        }

        if (in_array($jobPost->status, ['Hired', 'Online', 'Completed', 'Cancelled', 'Closed'])) {
            return redirect()->back()->with('error', 'This job cannot be edited in its current state.');
        }

        $hasActiveApplicants = $jobPost->responses()
            ->whereIn('status', ['Pending', 'Shortlisted'])
            ->exists();
        if ($hasActiveApplicants) {
            return redirect()->back()->with('error', 'Cannot edit a job with active applicants. Remove all applicants first.');
        }

        return view('job_posts.edit', compact('jobPost'));
    }

    public function update(Request $request, JobPost $jobPost)
    {
        if ((int) $jobPost->guardian_id !== (int) Auth::id()) {
            abort(403);
        }

        if (in_array($jobPost->status, ['Hired', 'Online', 'Completed', 'Cancelled', 'Closed'])) {
            return redirect()->back()->with('error', 'This job cannot be edited in its current state.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'class_level' => 'required|string|max:100',
            'expected_salary' => 'required|numeric|min:1',
            'location' => 'required|string|max:255',
            'medium' => 'required|in:Bangla,English,Both',
            'mode' => 'required|in:Online,Offline,Both',
            'description' => 'nullable|string|max:1000',
        ]);

        $jobPost->update($validated);

        return redirect()->route('job_posts.show', $jobPost)
            ->with('success', 'Job post updated!');
    }

    public function destroy(JobPost $jobPost)
    {
        if ((int) $jobPost->guardian_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($jobPost->status !== 'Open') {
            return redirect()->back()->with('error', 'Only Open posts can be deleted.');
        }

        DB::transaction(function () use ($jobPost) {
            $jobPost->responses()->update(['status' => 'Rejected']);
            $jobPost->delete();
        });

        return redirect()->route('job_posts.index')->with('success', 'Job post deleted.');
    }

    public function shortlist(Request $request, JobPost $jobPost)
    {
        if ((int) $jobPost->guardian_id !== (int) Auth::id()) {
            abort(403);
        }

        $request->validate(['response_id' => 'required|integer']);
        $response = JobPostResponse::findOrFail($request->response_id);

        if ($response->shortlisted) {
            return redirect()->back()->with('error', 'This tutor is already shortlisted.');
        }

        $guardian = Auth::user()->guardian;
        $subscription = $guardian
            ? Subscription::forGuardian($guardian->id)
                ->where('status', 'active')
                ->where('expires_at', '>=', now())
                ->latest()
                ->first()
            : null;
        $limit = ($subscription && $subscription->plan_name === 'Pro') ? 20 : 5;

        if ($jobPost->shortlisted_count >= $limit) {
            $msg = "Shortlist limit of {$limit} reached.";
            if ($limit === 5) {
                $msg .= ' Upgrade to Pro to shortlist up to 20 tutors.';
            }

            return redirect()->back()->with('error', $msg);
        }

        $response->status = 'Shortlisted';
        $response->shortlisted = true;
        $response->save();

        $jobPost->shortlisted_count += 1;
        if ($jobPost->status === 'Open') {
            $jobPost->status = 'Shortlisting';
        }
        $jobPost->save();

        return redirect()->back()->with('success', 'Tutor shortlisted!');
    }

    public function removeShortlist(Request $request, JobPost $jobPost)
    {
        if ((int) $jobPost->guardian_id !== (int) Auth::id()) {
            abort(403);
        }

        $request->validate(['response_id' => 'required|integer']);
        $response = JobPostResponse::findOrFail($request->response_id);

        $response->status = 'Pending';
        $response->shortlisted = false;
        $response->save();

        if ($jobPost->shortlisted_count > 0) {
            $jobPost->shortlisted_count -= 1;
        }
        $jobPost->save();

        return redirect()->back()->with('success', 'Removed from shortlist.');
    }

    public function rejectApplicant(Request $request, JobPost $jobPost)
    {
        if ((int) $jobPost->guardian_id !== (int) Auth::id()) {
            abort(403);
        }

        $request->validate(['response_id' => 'required|integer']);
        $response = JobPostResponse::findOrFail($request->response_id);

        if ($response->shortlisted) {
            return redirect()->back()
                ->with('error', 'Cannot reject a shortlisted tutor. Remove from shortlist first.');
        }

        $response->status = 'Rejected';
        $response->save();

        return redirect()->back()->with('success', 'Applicant rejected.');
    }
}
