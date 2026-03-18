<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        if ($role === 'guardian') {
            return view('dashboard.guardian');
        }

        if ($role === 'tutor') {
            return view('dashboard.tutor');
        }

        if ($role === 'admin') {
            return view('dashboard.admin');
        }

        // No role yet — send to role selection
        return redirect()->route('select_role_redirect');
    }
}
