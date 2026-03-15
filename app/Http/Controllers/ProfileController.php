<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tutor;
use App\Models\Guardian;
use App\Models\TutorProfile;
use App\Models\VerificationDocument;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $tutor = Tutor::where('user_id', $user->id)->first();
        $guardian = Guardian::where('user_id', $user->id)->first();
        $tutorProfile = TutorProfile::where('tutor_id', $user->id)->first();
        $verificationDocuments = VerificationDocument::where('tutor_id', $user->id)->get();

        $completionPercentage = 0;

        if ($user->role === 'TUTOR') {
            $fields = [
                $user->name,
                $user->email,
                optional($tutorProfile)->profile_picture,
                optional($tutorProfile)->contact_no,
                optional($tutorProfile)->cv,
                optional($tutorProfile)->educational_institutions,
                optional($tutorProfile)->work_experience,
                optional($tutorProfile)->teaching_method,
                optional($tutorProfile)->availability,
                optional($tutorProfile)->preferred_mediums,
                optional($tutorProfile)->preferred_subjects,
                optional($tutorProfile)->expected_salary
            ];

            $filled = collect($fields)->filter(function ($value) {
                return !is_null($value) && $value !== '';
            })->count();

            $completionPercentage = round(($filled / count($fields)) * 100);
        }

        return view('profile.show', compact(
            'user',
            'tutor',
            'guardian',
            'tutorProfile',
            'verificationDocuments',
            'completionPercentage'
        ));
    }

    public function edit()
    {
        $user = Auth::user();
        $tutor = Tutor::where('user_id', $user->id)->first();
        $guardian = Guardian::where('user_id', $user->id)->first();
        $tutorProfile = TutorProfile::where('tutor_id', $user->id)->first();
        $verificationDocuments = VerificationDocument::where('tutor_id', $user->id)->get()->keyBy('doc_type');

        return view('profile.edit', compact('user', 'tutor', 'guardian', 'tutorProfile', 'verificationDocuments'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_no' => 'nullable|string|max:50',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cv' => 'nullable|mimes:pdf|max:2048',
            'nid_document' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'occupation_document' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        if ($user->role === 'TUTOR') {
            $tutor = Tutor::where('user_id', $user->id)->first();

            if (!$tutor) {
                $tutor = new Tutor();
                $tutor->user_id = $user->id;
            }

            $tutorProfile = TutorProfile::firstOrNew(['tutor_id' => $user->id]);

            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                $tutorProfile->profile_picture = $profilePicturePath;
            }

            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cv_uploads', 'public');
                $tutorProfile->cv = $cvPath;
            }

            $tutorProfile->tutor_id = $user->id;
            $tutorProfile->name = $request->name;
            $tutorProfile->email = $request->email;
            $tutorProfile->contact_no = $request->contact_no;
            $tutorProfile->educational_institutions = $request->educational_institutions;
            $tutorProfile->work_experience = $request->work_experience;
            $tutorProfile->teaching_method = $request->teaching_method;
            $tutorProfile->availability = $request->availability;
            $tutorProfile->preferred_mediums = $request->preferred_mediums;
            $tutorProfile->preferred_subjects = $request->preferred_subjects;
            $tutorProfile->expected_salary = $request->expected_salary;
            $tutorProfile->save();

            if ($request->hasFile('nid_document')) {
                $nidPath = $request->file('nid_document')->store('verification_documents', 'public');

                VerificationDocument::updateOrCreate(
                    ['tutor_id' => $user->id, 'doc_type' => 'NID'],
                    [
                        'file_path' => $nidPath,
                        'status' => 'PENDING',
                        'review_note' => null
                    ]
                );
            }

            if ($request->hasFile('occupation_document')) {
                $occupationPath = $request->file('occupation_document')->store('verification_documents', 'public');

                VerificationDocument::updateOrCreate(
                    ['tutor_id' => $user->id, 'doc_type' => 'OCCUPATION_CARD'],
                    [
                        'file_path' => $occupationPath,
                        'status' => 'PENDING',
                        'review_note' => null
                    ]
                );
            }
        }

        if ($user->role === 'GUARDIAN') {
            $guardian = Guardian::firstOrNew(['user_id' => $user->id]);
            $guardian->address = $request->address;
            $guardian->latitude = $request->latitude;
            $guardian->longitude = $request->longitude;
            $guardian->number_of_children = $request->number_of_children;
            $guardian->preferred_subjects = $request->preferred_subjects;
            $guardian->save();
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
}