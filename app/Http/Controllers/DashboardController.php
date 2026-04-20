<?php

namespace App\Http\Controllers;

use App\Models\HireConfirmation;
use App\Models\Tutor;
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
            $tutor = Tutor::where('tutor_id', $user->id)->first();
            $pendingHires = $tutor
                ? HireConfirmation::where('tutor_id', $tutor->tutor_id)
                    ->where('status', 'awaiting_tutor')
                    ->with('job')
                    ->get()
                : collect();

            return view('dashboard.tutor', compact('pendingHires'));
        }

        if ($role === 'admin') {
            $cancellationRequests = \App\Models\HireConfirmation::where('status', 'cancellation_requested')
                ->orderBy('updated_at', 'desc')
                ->get();

            return view('dashboard.admin', compact('cancellationRequests'));
        }

        return redirect()->route('select_role_redirect');
    }
}
