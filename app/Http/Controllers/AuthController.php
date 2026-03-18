<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $action = $request->input('action_type');
        if ($action === 'logout') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('landing_page');
        }
        if ($action === 'login') {
            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required|min:2',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();

                // No role yet → go pick one
                if (is_null($user->role) || $user->role === 'user') {
                    return redirect()->route('select_role_redirect');
                }

                // Has role but profile not 100% → go edit
                return redirect()->route('profile.edit');
            }

            return back()->withErrors(['error_login' => 'Login Failed! Invalid Credentials.'])->withInput();
        }

        if ($action === 'signup') {
            $credentials = $request->validate([
                'name'              => 'required',
                'email'             => 'required|email|unique:users,email',
                'password'          => 'required|min:2|confirmed',
            ]);

            $user = User::create([
                'name'     => $credentials['name'],
                'email'    => $credentials['email'],
                'password' => bcrypt($credentials['password']),
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            // Always go to role selection after signup
            return redirect()->route('select_role_redirect');
        }
    }

    public function index() {}
    public function create(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}