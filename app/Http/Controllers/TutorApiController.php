<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TutorProfile;
use Illuminate\Http\Request;

class TutorApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    // Start query with eager loading tutor
    $query = TutorProfile::with('tutor');

    // Checking if any filter are applied
    $filter_applied = False;


    // Filter salary
    if ($request->filled('min_salary')) {
        $query->where('expected_salary', '>=', $request->min_salary);
        $filter_applied = True;
    }
    if ($request->filled('max_salary')) {
        $query->where('expected_salary', '<=', $request->max_salary);
        $filter_applied = True;
    }

    // Filter tutor rating
    if ($request->filled('min_rating')) {
        $query->whereHas('tutor', function ($q) use ($request) {
            $q->where('ratings', '>=', $request->min_rating);
        });
    }
    if ($request->filled('max_rating')) {
        $query->whereHas('tutor', function ($q) use ($request) {
            $q->where('ratings', '<=', $request->max_rating);
        });
        $filter_applied = True;
    }

    // Filter tutor name
    if ($request->filled('tutor_name')) {
        $query->where('name', 'like', '%' . $request->tutor_name . '%');
        $filter_applied = True;
    }

    // Filter subjects
    if ($request->filled('subjects')) {
        $subjects = explode(',', $request->subjects);
        foreach ($subjects as $subject) {
            $query->orwhereJsonContains('preferred_subjects', $subject);
        }
        $filter_applied = True;
    }

    // Filter mediums
     if ($request->filled('mediums')) {
        $mediums = array_map('trim', explode(',', $request->mediums));
        foreach ($mediums as $medium) {
            $query->orwhereJsonContains('preferred_mediums', $medium);
        }
        $filter_applied = True;
    }

    // Filter classes
     if ($request->filled('class')) {
        $classes = array_map('trim', explode(',', $request->class));
        $classes = array_map('strval', $classes); // ensure strings

        $query->where(function ($q) use ($classes) {
            foreach ($classes as $class) {
                $q->orWhereJsonContains('preferred_classes', $class);
            }
        });
        $filter_applied = True;        
    }

    if (!$filter_applied) {
        // If no filter applied, return empty result to avoid overwhelming the frontend
        return response()->json([]);
    }
    // Get results
    $results = $query->get();

    // Map to return only profile fields + rating
    $results = $results->map(function ($profile) {
        return [
            'id' => $profile->tutor_id,
            'name' => $profile->name,
            'img' => $profile->profile_picture,
            'gender' => $profile->tutor->gender,
            'educational_institutions' => $profile->educational_institutions,
            'expected_salary' => $profile->expected_salary,
            'rating' => $profile->tutor->ratings ?? null, 
            'mediums' => $profile->preferred_mediums,
            'subjects' => $profile->preferred_subjects,
            'classes' => $profile->preferred_classes,
        ];
    });

    return response()->json($results);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
