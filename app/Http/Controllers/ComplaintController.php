<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\TuitionContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Show complaint form (for guardians and tutors).
     */
    public function create()
    {
        $user = Auth::user();
        $contracts = TuitionContract::where('status', 'ACTIVE')
            ->where(function ($query) use ($user) {
                $query->where('guardian_id', $user->id)
                    ->orWhere('tutor_id', $user->id);
            })
            ->with(['guardian', 'tutor'])
            ->get();

        return view('complaints.create', compact('contracts'));
    }

    /**
     * Store a new complaint.
     */
    public function store(Request $request)
    {
        $request->validate([
            'against_user' => 'required|exists:users,id',
            'contract_id' => [
                'required',
                'exists:tuition_contracts,id',
                function ($attribute, $value, $fail) {
                    $contract = \App\Models\TuitionContract::find($value);
                    if ($contract && $contract->status !== 'ACTIVE') {
                        $fail('You can only file a complaint for an ACTIVE contract.');
                    }
                },
            ],
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
        ]);

        Complaint::create([
            'filed_by' => Auth::id(),
            'against_user' => $request->against_user,
            'contract_id' => $request->contract_id,
            'subject' => $request->subject,
            'description' => $request->description,
        ]);

        return redirect()->route('dashboard')->with('success', 'Your complaint has been submitted. Our team will review it shortly.');
    }

    /**
     * Show complaints filed by the current user.
     */
    public function myComplaints()
    {
        $complaints = Complaint::where('filed_by', Auth::id())
            ->with(['accusedUser', 'contract'])
            ->latest()
            ->get();

        return view('complaints.index', compact('complaints'));
    }

    /**
     * Admin: List all complaints.
     */
    public function index()
    {
        $complaints = Complaint::with(['filer', 'accusedUser', 'contract'])
            ->latest()
            ->get();

        return view('admin.complaints', compact('complaints'));
    }

    /**
     * Admin: View complaint detail.
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['filer', 'accusedUser', 'contract', 'resolver']);

        return view('admin.complaint_show', compact('complaint'));
    }

    /**
     * Admin: Resolve or dismiss a complaint.
     */
    public function resolve(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:Resolved,Dismissed,Under Review',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $complaint->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        return redirect()->route('admin.complaints')->with('success', "Complaint #{$complaint->id} has been {$request->status}.");
    }
}
