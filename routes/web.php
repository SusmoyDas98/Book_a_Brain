<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Page_Redirection_Controller;
use App\Http\Middleware\ValidUser;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('landing_page');
});
Route::view('/login_or_signup','login_signup_page')->name('login_or_signup_page_redirect');

Route::resource('auth', AuthController::class)->only(['store', 'index', 'create', 'show', 'edit', 'update', 'destroy']);

Route::get('/select_role_page', [Page_Redirection_Controller::class, 'select_role_page'])->name('select_role_redirect')->middleware(ValidUser::class);;

// use App\Http\Controllers\GoogleController;

// Google authentication routes

Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback']);