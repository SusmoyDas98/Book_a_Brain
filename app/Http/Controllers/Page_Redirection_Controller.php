<?php

namespace App\Http\Controllers;

use App\Models\JobPostResponse;
use App\Models\Tutor;
use App\Models\TutorProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Unique;

class Page_Redirection_Controller extends Controller
{
    //
    public function login_signup_page()
    {
        return redirect()->route('login_signup_page');
    }

    public function select_role_page()
    {
        return view('select_role');
    }

    public function tutor_search_page()
    {
        return view('tutor_search');
    }

    public function tutor_profile_page($id)
    {
        $tutor = \App\Models\Tutor::where('tutor_id', $id)->firstOrFail();
        $tutorProfile = \App\Models\TutorProfile::where('tutor_id', $id)->firstOrFail();

        return view('tutor_profile', compact('tutor', 'tutorProfile'));
    }

    public function tutor_message_page($id)
    {

        return 'Tutor Message Page for Tutor ID:'.$id;
    }

    public function post_response_page()
    {
        $guardian_id = Auth::id();
        $tutorInfos = JobPostResponse::query()->where('guardian_id', $guardian_id)->get();
        // $tutorProfile = \App\Models\TutorProfile::where('tutor_id', $id)->firstOrFail();
        $tutor_ids = $tutorInfos->pluck('tutor_id')->unique();
        $tutor_ratings_collection = Tutor::whereIn('tutor_id', $tutor_ids)->pluck('ratings', 'tutor_id');
        // return $tutorInfos;
        return view('post_response', compact('tutorInfos', 'guardian_id', "tutor_ratings_collection"));
    }
}
