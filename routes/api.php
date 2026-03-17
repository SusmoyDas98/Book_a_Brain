<?php

use App\Http\Controllers\TutorApiController;
use App\Models\JobPostResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
//tutor apis 
Route::apiResource('/tutor_profiles', TutorApiController::class);


