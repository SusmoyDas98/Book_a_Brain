<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\Tutor;
use App\Models\TutorProfile;
use App\Models\VerificationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user                 = Auth::user();
        $tutor                = Tutor::where('tutor_id', $user->id)->first();
        $guardian             = Guardian::where('guardian_id', $user->id)->first();
        $tutorProfile         = TutorProfile::where('tutor_id', $user->id)->first();
        $verificationDocuments = VerificationDocument::where('tutor_id', $user->id)->get();
        $completionPercentage = 0;

        if (strtolower($user->role) === 'tutor') {
            $completionPercentage = $this->calculateTutorCompletion($user, $tutorProfile, $verificationDocuments);
        }
        if (strtolower($user->role) === 'guardian') {
            $completionPercentage = $this->calculateGuardianCompletion($user, $guardian);
        }

        return view('profile.show', compact(
            'user', 'tutor', 'guardian', 'tutorProfile', 'verificationDocuments', 'completionPercentage'
        ));
    }

    public function edit()
    {
        $user                  = Auth::user();
        $tutor                 = Tutor::where('tutor_id', $user->id)->first();
        $guardian              = Guardian::where('guardian_id', $user->id)->first();
        $tutorProfile          = TutorProfile::where('tutor_id', $user->id)->first();
        $verificationDocuments = VerificationDocument::where('tutor_id', $user->id)->get()->keyBy('doc_type');
        $completionPercentage  = 0;

        if (strtolower($user->role) === 'tutor') {
            $completionPercentage = $this->calculateTutorCompletion($user, $tutorProfile, $verificationDocuments->values());
        }
        if (strtolower($user->role) === 'guardian') {
            $completionPercentage = $this->calculateGuardianCompletion($user, $guardian);
        }

        return view('profile.edit', compact(
            'user', 'tutor', 'guardian', 'tutorProfile', 'verificationDocuments', 'completionPercentage'
        ));
    }

    public function update(Request $request)
    {
        // dd($request->work_experience)  ;
        $user = Auth::user();
        // LOG 1: Check if the request is even hitting the controller
        Log::info('Update method reached for user: ' . $user->id);

        
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|max:255|unique:users,email,' . $user->id,
            'contact_no'          => 'nullable|string|max:50',
            'gender'              => 'nullable|string|in:Male,Female,Other,Prefer not to say',
            'profile_picture'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cv'                  => 'nullable|mimes:pdf|max:2048',
            'nid_document'        => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'occupation_document' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'guardian_nid'        => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        if (strtolower($user->role) === 'tutor') {
            $tutorProfile = TutorProfile::firstOrNew(['tutor_id' => $user->id]);
            $tutor = Tutor::firstOrNew(['tutor_id' => $user->id]);

            if ($request->hasFile('profile_picture')) {
                $tutorProfile->profile_picture = $request->file('profile_picture')
                    ->store('profile_pictures', 'public');
            }
            if ($request->hasFile('cv')) {
                $tutorProfile->cv = $request->file('cv')->store('cv_uploads', 'public');
            }
            // updating the tutor table 
            $tutor->tutor_id = $user->id;
            $tutor->gender = $request->gender;
            $tutor->cv_pdf = Storage::url($tutorProfile->cv);
            $tutor->save();
            
            
            // updating the tutor profile table
            $tutorProfile->tutor_id                 = $user->id;
            $tutorProfile->name                     = $request->name;
            $tutorProfile->email                    = $request->email;
            $tutorProfile->contact_no               = $request->contact_no;
            $tutorProfile->gender                   = $request->gender;
            $tutorProfile->educational_institutions = $request->educational_institutions;
            $tutorProfile->work_experience          = $request->work_experience;
            $tutorProfile->teaching_method          = $request->teaching_method;
            $available_time = array_map('trim', explode(',',$request->availability));
            $tutorProfile->availability             = $available_time;
            $tutorProfile->preferred_mediums        = $request->preferred_mediums;
            $subjects_array = array_map('trim', explode(',', $request->preferred_subjects));
            $subjects = array_map(function ($subject) {
                return ucfirst(strtolower($subject));
            }, $subjects_array);
            $tutorProfile->preferred_subjects       = $subjects;
            $classes = array_map('trim', explode(',', $request->preferred_classes));
            $tutorProfile->preferred_classes       = $classes;
            $tutorProfile->expected_salary          = $request->expected_salary;
            $tutorProfile->save();

            if ($request->hasFile('nid_document')) {
                $path = $request->file('nid_document')->store('verification_documents', 'public');
                VerificationDocument::updateOrCreate(
                    ['tutor_id' => $user->id, 'doc_type' => 'NID'],
                    ['file_path' => $path,
                     'status' => 'PENDING', 'review_note' => null]
                );
                $tutor->nid_card =  Storage::url($path);
                $tutor->save();
            }
            // Reset verification status to PENDING when new docs uploaded
            if ($request->hasFile('nid_document') || $request->hasFile('occupation_document')) {
                \App\Http\Controllers\VerificationController::resetToPending($user->id);
            }
            if ($request->hasFile('occupation_document')) {
                $path = $request->file('occupation_document')->store('verification_documents', 'public');
                VerificationDocument::updateOrCreate(
                    ['tutor_id' => $user->id, 'doc_type' => 'OCCUPATION_CARD'],
                    ['file_path' => $path,
                     'status' => 'PENDING', 'review_note' => null]
                );
                $tutor->student_id_card = Storage::url($path);
                $tutor->save();
            }
        }

        if (strtolower($user->role) === 'guardian') {
            $guardian = Guardian::firstOrNew(['guardian_id' => $user->id]);

            if ($request->hasFile('profile_picture')) {
                $guardian->profile_picture = $request->file('profile_picture')
                    ->store('profile_pictures', 'public');
            }

            $guardian->guardian_id    = $user->id;
            $guardian->name       = $request->guardian_name;
            $guardian->contact_no = $request->guardian_contact_no;
            $guardian->email = $request->email;
            $guardian->gender     = $request->gender;
            $guardian->address    = $request->address;
            $guardian->location   = [
                'lat' => $request->lat,
                'lng' => $request->lng,
            ];
            if ($request->hasFile('guardian_nid')) {
                $guardian->nid_card = $request->file('guardian_nid')
                    ->store('nid_uploads', 'public');
            }
            $guardian->save();
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
    public function saveRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:tutor,guardian',
        ]);

        $user = Auth::user();
        $user->role = $request->role;
        $user->save();

        // After picking role, always go to edit page
        return redirect()->route('profile.edit');
    }

    public function confirmProfile()
    {
        $user                 = Auth::user();
        $tutorProfile         = \App\Models\TutorProfile::where('tutor_id', $user->id)->first();
        $guardian             = \App\Models\Guardian::where('guardian_id', $user->id)->first();
        $verificationDocuments = \App\Models\VerificationDocument::where('tutor_id', $user->id)->get();

        if (strtolower($user->role) === 'tutor') {
            $completion = $this->calculateTutorCompletion($user, $tutorProfile, $verificationDocuments);
        } else {
            $completion = $this->calculateGuardianCompletion($user, $guardian);
        }

        if ($completion < 100) {
            return redirect()->route('profile.edit')
                ->with('error', 'You must complete 100% of your profile before confirming.');
        }

        // Teammate will build the dashboard — route is ready
        return redirect()->route('dashboard');
    }
    private function calculateTutorCompletion($user, $tutorProfile, $verificationDocuments)
    {
        $nidDoc        = $verificationDocuments->firstWhere('doc_type', 'NID');
        $occupationDoc = $verificationDocuments->firstWhere('doc_type', 'OCCUPATION_CARD');

        $fields = [
            $user->name,
            $user->email,
            optional($tutorProfile)->contact_no,
            optional($tutorProfile)->gender,
            optional($tutorProfile)->profile_picture,
            optional($tutorProfile)->cv,
            optional($tutorProfile)->educational_institutions,
            optional($tutorProfile)->work_experience,
            optional($tutorProfile)->teaching_method,
            optional($tutorProfile)->availability,
            optional($tutorProfile)->preferred_mediums,
            optional($tutorProfile)->preferred_subjects,
            optional($tutorProfile)->expected_salary,
            optional($nidDoc)->file_path,
            optional($occupationDoc)->file_path,
        ];

        $filled = collect($fields)->filter(fn($v) => !is_null($v) && $v !== '')->count();
        return round(($filled / count($fields)) * 100);
    }

    private function calculateGuardianCompletion($user, $guardian)
    {
        $fields = [
            $user->name,
            $user->email,
            optional($guardian)->name,
            optional($guardian)->email,
            optional($guardian)->contact_no,
            optional($guardian)->gender,
            optional($guardian)->profile_picture,
            optional($guardian)->nid_card,
            optional($guardian)->address,
            optional($guardian)->location,
        ];

        $filled = collect($fields)->filter(fn($v) => !is_null($v) && $v !== '')->count();
        return round(($filled / count($fields)) * 100);
    }
}
