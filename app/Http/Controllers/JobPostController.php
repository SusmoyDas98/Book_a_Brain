<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobPostResponse;
use App\Models\TutorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'title'           => 'required|string|max:255',
            'subject'         => 'required|string|max:255',
            'class_level'     => 'required|string|max:100',
            'expected_salary' => 'required|numeric|min:0',
            'location'        => 'required|string|max:255',
            'medium'          => 'required|in:Bangla,English,Both',
            'mode'            => 'required|in:Online,Offline,Both',
            'description'     => 'nullable|string|max:1000',
        ]);

        JobPost::create(array_merge($validated, [
            'guardian_id'       => Auth::id(),
            'status'            => 'Open',
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

        if (in_array($jobPost->status, ['Hired', 'Closed'])) {
            return redirect()->back()->with('error', 'Cannot edit a Hired or Closed post.');
        }

        return view('job_posts.edit', compact('jobPost'));
    }

    public function update(Request $request, JobPost $jobPost)
    {
        if ((int) $jobPost->guardian_id !== (int) Auth::id()) {
            abort(403);
        }

        if (in_array($jobPost->status, ['Hired', 'Closed'])) {
            return redirect()->back()->with('error', 'Cannot edit a Hired or Closed post.');
        }

        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'subject'         => 'required|string|max:255',
            'class_level'     => 'required|string|max:100',
            'expected_salary' => 'required|numeric|min:0',
            'location'        => 'required|string|max:255',
            'medium'          => 'required|in:Bangla,English,Both',
            'mode'            => 'required|in:Online,Offline,Both',
            'description'     => 'nullable|string|max:1000',
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

        $jobPost->delete();

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

        if ($jobPost->shortlisted_count >= 5) {
            return redirect()->back()->with('error', 'Maximum 5 tutors can be shortlisted per post.');
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
