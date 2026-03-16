<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Page_Redirection_Controller;
use App\Http\Middleware\ValidUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationRuleParser;
use Laravel\Socialite\Facades\Socialite;
// Landing Page Route
Route::get('/', function () {
    return view('landing_page');
});

//  Login and Signup Page Route

Route::view('/login_or_signup','login_signup_page')->name('login_or_signup_page_redirect');

// Google authentication routes

Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback']);

//  Login and Signup controller Route

Route::resource('auth', AuthController::class)->only(['store', 'index', 'create', 'show', 'edit', 'update', 'destroy']);

Route::get('/select_role_page', [Page_Redirection_Controller::class, 'select_role_page'])->name('select_role_redirect')->middleware(ValidUser::class);;

// tutor search page 
Route::get('/tutor_search', [Page_Redirection_Controller::class, 'tutor_search_page'])->name('tutor_search_redirect')->middleware(ValidUser::class);

Route::get('/tutor_profile/{id}', [Page_Redirection_Controller::class, 'tutor_profile_page'])->name('tutor_profile_redirect')->middleware(ValidUser::class);

Route::get('/tutor_message/{id}', [Page_Redirection_Controller::class, 'tutor_message_page'])->name('tutor_message_redirect')->middleware(ValidUser::class);