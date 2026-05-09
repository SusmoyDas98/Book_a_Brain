<?php

namespace App\Http\Controllers;

use App\Models\JobPostResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        // return "Hello World";
        // return $request;
        $shortlist_limit = Auth::user()->isPro() ? 20 : 3;
        $shortlist_remaining = max($shortlist_limit - JobPostResponse::where('guardian_id', Auth::id())->where('shortlisted', 1)->count(), 0);

        $inputs = collect($request->all())->except(['_token', '_method']);
        foreach ($inputs as $key => $value) {
            $post_id = $key;
            $post = JobPostResponse::find($post_id);
            if (! $post) {
                continue;
            }
            $shortlist_val = $value;
            $shortlist_val_str = (string) $shortlist_val;

            // check how many shortlisted already
            // if ($post->guardian_id === Auth::id() && $post->shortlisted == "1"){
            //         $shortlist_limit = max($shortlist_limit - 1, 0);
            //     }
            if ($post->shortlisted != $shortlist_val_str) {
                if ($post->shortlisted == 0 && $post->guardian_id === Auth::id() && $shortlist_remaining == 0) {
                    return redirect()->back()->with('error', 'Shortlisting limit exceeded for Free Version!!! Subscribe for shortlisting upto 20 tutors.');
                }
                $post->shortlisted = $shortlist_val_str;
                $post->save();

                $statusMsg = $shortlist_val_str === '1' ? 'shortlisted' : 'not shortlisted';
                \App\Models\AppNotification::create([
                    'recipient_type' => 'tutor',
                    'recipient_id' => $post->tutor_id,
                    'title' => 'Application Update',
                    'message' => 'Your application has been '.$statusMsg.' by the guardian.',
                    'type' => 'system',
                    'related_job_id' => $post->job_post_id,
                    'is_read' => false,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Changes saved successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
