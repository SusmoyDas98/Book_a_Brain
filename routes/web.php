<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Page_Redirection_Controller;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\ValidUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\TuitionContractController;

Route::get('/', function () {
    return view('landing_page');
})->name('landing_page');

Route::view('/login_or_signup', 'login_signup_page')->name('login_or_signup_page_redirect');

Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::resource('auth', AuthController::class)->only(['store', 'index', 'create', 'show', 'edit', 'update', 'destroy']);

Route::get('/select_role_page', [Page_Redirection_Controller::class, 'select_role_page'])
    ->name('select_role_redirect')
    ->middleware(ValidUser::class);

Route::get('/tutor_search', [Page_Redirection_Controller::class, 'tutor_search_page'])
    ->name('tutor_search_redirect')
    ->middleware(ValidUser::class);

Route::get('/tutor_profile/{id}', [Page_Redirection_Controller::class, 'tutor_profile_page'])
    ->name('tutor_profile_redirect')
    ->middleware(ValidUser::class);

Route::get('/tutor_message/{id}', [Page_Redirection_Controller::class, 'tutor_message_page'])
    ->name('tutor_message_redirect')
    ->middleware(ValidUser::class);

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/select-role', [ProfileController::class, 'saveRole'])->name('role.save');
    Route::post('/profile/confirm', [ProfileController::class, 'confirmProfile'])->name('profile.confirm');
    // Guardian routes
    Route::get('/contracts',          [TuitionContractController::class, 'guardianIndex'])->name('contracts.guardian');
    Route::get('/contracts/hire',     [TuitionContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts',         [TuitionContractController::class, 'store'])->name('contracts.store');

    // Tutor routes
    Route::get('/contracts/tutor',    [TuitionContractController::class, 'tutorIndex'])->name('contracts.tutor');
    Route::post('/contracts/{contract}/accept',  [TuitionContractController::class, 'accept'])->name('contracts.accept');
    Route::post('/contracts/{contract}/decline', [TuitionContractController::class, 'decline'])->name('contracts.decline');

    // Shared
    Route::get('/contracts/{contract}',          [TuitionContractController::class, 'show'])->name('contracts.show');
    Route::post('/contracts/{contract}/log',     [TuitionContractController::class, 'logSession'])->name('contracts.log');
    Route::post('/contracts/{contract}/end',     [TuitionContractController::class, 'end'])->name('contracts.end');
    Route::post('/contracts/{contract}/notes',   [TuitionContractController::class, 'updateNotes'])->name('contracts.notes');
    Route::post('/session-logs/{sessionLog}/note', [TuitionContractController::class, 'guardianNote'])->name('contracts.guardian_note');

    Route::get('/dashboard', function () {
        return 'Dashboard coming soon.';
    })->name('dashboard');
});

Route::get('/dev/profile-preview', function () {
    Auth::login(User::first());
    return redirect('/profile');
});