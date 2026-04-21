<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to Google login page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google after login.
     */
    public function handleGoogleCallback()
    {
        try {
            // Fetch user info from Google
            $googleUser = Socialite::driver('google')->user();

            // First, try to find the user by Google ID
            $existingUser = User::where('google_id', $googleUser->getId())->first();

            // If not found by Google ID, check email (manual signup)
            if (! $existingUser) {
                $existingUser = User::where('email', $googleUser->getEmail())->first();

                // If user exists by email but google_id is empty, update it
                if ($existingUser && ! $existingUser->google_id) {
                    $existingUser->google_id = $googleUser->getId();
                    $existingUser->google_token = $googleUser->token ?? null;
                    $existingUser->google_refresh_token = $googleUser->refreshToken ?? null;
                    $existingUser->save();
                }
            }

            if ($existingUser) {
                Auth::login($existingUser);
                request()->session()->regenerate();

                // return redirect()->route(''); // Replace with your dashboard route
                return 'User Dashboard';
            }

            // Create new user if not exists
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'google_token' => $googleUser->token ?? null,
                'google_refresh_token' => $googleUser->refreshToken ?? null,
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(16)),
            ]);

            Auth::login($user);
            request()->session()->regenerate();

            return redirect()->route('select_role_redirect');

        } catch (\Exception $e) {
            // Catch all errors (Socialite, DB, etc.)
            return redirect()->route('login_or_signup_page_redirect')
                ->withErrors(['google_error' => 'Something went wrong. Please try again.']);
        }
    }
}
