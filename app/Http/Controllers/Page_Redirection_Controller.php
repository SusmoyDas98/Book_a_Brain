<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Page_Redirection_Controller extends Controller
{
    //
    public function login_signup_page(){
        return redirect()->route("login_signup_page");
    }
    public function select_role_page(){
            return view('select_role');
    }
}
