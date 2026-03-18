<?php

namespace App\Http\Controllers;

use App\Models\TuitionContract;
use App\Models\SessionLog;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TuitionContractController extends Controller
{
    // ── GUARDIAN: Show all their contracts ──────────────────────────────
    public function guardianIndex()
    {
        $user      = Auth::user();
        $contracts = TuitionContract::where('guardian_id', $user->id)
                        ->with('tutor')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('contracts.guardian_index', compact('contracts'));
    }

    // ── TUTOR: Show all their contracts ─────────────────────────────────
    public function tutorIndex()
    {
        $user      = Auth::user();
        $contracts = TuitionContract::where('tutor_id', $user->id)
                        ->with('guardian')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('contracts.tutor_index', compact('contracts'));
    }

    // ── GUARDIAN: Show hire form ─────────────────────────────────────────
    public function create(Request $request)
    {
        $tutorId      = $request->query('tutor_id');
        $tutor        = User::findOrFail($tutorId);
        $tutorProfile = TutorProfile::where('tutor_id', $tutorId)->first();

        // Check if a contract already exists between this guardian and tutor
        $existing = TuitionContract::where('guardian_id', Auth::id())
                        ->where('tutor_id', $tutorId)
                        ->whereIn('status', ['PENDING', 'ACTIVE'])
                        ->first();

        return view('contracts.create', compact('tutor', 'tutorProfile', 'existing'));
    }

    // ── GUARDIAN: Submit hire request ────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'tutor_id'   => 'required|exists:users,id',
            'subject'    => 'required|string|max:255',
            'schedule'   => 'required|string|max:255',
            'salary'     => 'required|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        // Prevent duplicate active/pending contracts
        $existing = TuitionContract::where('guardian_id', Auth::id())
                        ->where('tutor_id', $request->tutor_id)
                        ->whereIn('status', ['PENDING', 'ACTIVE'])
                        ->first();

        if ($existing) {
            return back()->with('error', 'You already have an active or pending contract with this tutor.');
        }

        TuitionContract::create([
            'guardian_id' => Auth::id(),
            'tutor_id'    => $request->tutor_id,
            'subject'     => $request->subject,
            'schedule'    => $request->schedule,
            'salary'      => $request->salary,
            'start_date'  => $request->start_date,
            'status'      => 'PENDING',
        ]);

        return redirect()->route('contracts.guardian')
            ->with('success', 'Hire request sent! Waiting for tutor to accept.');
    }

    // ── TUTOR: Accept contract ───────────────────────────────────────────
    public function accept(TuitionContract $contract)
    {
        if ($contract->tutor_id !== Auth::id()) abort(403);
        if ($contract->status !== 'PENDING') abort(403);

        $contract->update(['status' => 'ACTIVE']);

        return redirect()->route('contracts.tutor')
            ->with('success', 'Contract accepted! You can now log sessions.');
    }

    // ── TUTOR: Decline contract ──────────────────────────────────────────
    public function decline(TuitionContract $contract)
    {
        if ($contract->tutor_id !== Auth::id()) abort(403);
        if ($contract->status !== 'PENDING') abort(403);

        $contract->update(['status' => 'ENDED', 'ended_at' => now()]);

        return redirect()->route('contracts.tutor')
            ->with('success', 'Contract declined.');
    }

    // ── BOTH: View contract detail + session logs ────────────────────────
    public function show(TuitionContract $contract)
    {
        $user = Auth::user();

        if ($contract->guardian_id !== $user->id && $contract->tutor_id !== $user->id) {
            abort(403);
        }

        $sessionLogs  = $contract->sessionLogs()->get();
        $tutorProfile = TutorProfile::where('tutor_id', $contract->tutor_id)->first();

        // Current week's Monday
        $currentWeekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $currentLog = $sessionLogs->firstWhere('week_start', $currentWeekStart);

        return view('contracts.show', compact(
            'contract', 'sessionLogs', 'tutorProfile', 'currentWeekStart', 'currentLog'
        ));
    }

    // ── TUTOR: Save weekly session log ───────────────────────────────────
    public function logSession(Request $request, TuitionContract $contract)
    {
        if ($contract->tutor_id !== Auth::id()) abort(403);
        if ($contract->status !== 'ACTIVE') abort(403);

        $request->validate([
            'week_start' => 'required|date',
            'tutor_note' => 'nullable|string|max:1000',
        ]);

        SessionLog::updateOrCreate(
            ['contract_id' => $contract->id, 'week_start' => $request->week_start],
            [
                'mon'        => $request->boolean('mon'),
                'tue'        => $request->boolean('tue'),
                'wed'        => $request->boolean('wed'),
                'thu'        => $request->boolean('thu'),
                'fri'        => $request->boolean('fri'),
                'sat'        => $request->boolean('sat'),
                'sun'        => $request->boolean('sun'),
                'tutor_note' => $request->tutor_note,
            ]
        );

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Session log saved!');
    }

    // ── GUARDIAN: Add note to a session log ─────────────────────────────
    public function guardianNote(Request $request, SessionLog $sessionLog)
    {
        if ($sessionLog->contract->guardian_id !== Auth::id()) abort(403);

        $request->validate(['guardian_note' => 'nullable|string|max:1000']);

        $sessionLog->update(['guardian_note' => $request->guardian_note]);

        return redirect()->route('contracts.show', $sessionLog->contract_id)
            ->with('success', 'Note saved!');
    }

    // ── BOTH: End contract ───────────────────────────────────────────────
    public function end(Request $request, TuitionContract $contract)
    {
        $user = Auth::user();

        if ($contract->guardian_id !== $user->id && $contract->tutor_id !== $user->id) {
            abort(403);
        }
        if ($contract->status !== 'ACTIVE') abort(403);

        $contract->update(['status' => 'ENDED', 'ended_at' => now()]);

        $route = strtolower($user->role) === 'tutor' ? 'contracts.tutor' : 'contracts.guardian';
        return redirect()->route($route)->with('success', 'Contract ended.');
    }

    // ── GUARDIAN: Update their notes on a contract ───────────────────────
    public function updateNotes(Request $request, TuitionContract $contract)
    {
        $user = Auth::user();
        if ($contract->guardian_id !== $user->id && $contract->tutor_id !== $user->id) abort(403);

        $request->validate([
            'guardian_notes' => 'nullable|string|max:2000',
            'tutor_notes'    => 'nullable|string|max:2000',
        ]);

        if ($contract->guardian_id === $user->id) {
            $contract->update(['guardian_notes' => $request->guardian_notes]);
        } else {
            $contract->update(['tutor_notes' => $request->tutor_notes]);
        }

        return redirect()->route('contracts.show', $contract)->with('success', 'Notes updated!');
    }
}