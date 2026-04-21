<?php

namespace App\Http\Controllers;

use App\Models\JobPostResponse;
use Illuminate\Http\Request;

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
        $inputs = collect($request->all())->except(['_token', '_method']);
        foreach ($inputs as $key => $value) {
            $post_id = $key;
            $post = JobPostResponse::find($post_id);
            if (! $post) {
                continue;
            }
            $shortlist_val = $value;
            $shortlist_val_str = (string) $shortlist_val;
            if ($post->shortlisted != $shortlist_val_str) {
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

            return redirect()->back()->with('success', 'Changes saved successfully!');

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
