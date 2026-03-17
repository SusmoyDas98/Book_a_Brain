<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobPostResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TutorProfile;
use App\Models\Tutor;

class Page_Redirection_Controller extends Controller
{
    //
    public function login_signup_page(){
        return redirect()->route("login_signup_page");
    }
    public function select_role_page(){
            return view('select_role');
    }

    public function  tutor_search_page(){
        return view('tutor_search');
    }
    public function tutor_profile_page($id){
        return "Tutor Profile Page for Tutor ID:" . $id;
    }
    public function tutor_message_page($id){
        return "Tutor Message Page for Tutor ID:" . $id;
    }
    public function post_response_page(){
        $guardian_id =  "36";
        $tutorInfos = JobPostResponse::query()->where('guardian_id', $guardian_id)->get();
        // return $tutorInfos;
        return view('post_response', compact('tutorInfos'));
    }
}
