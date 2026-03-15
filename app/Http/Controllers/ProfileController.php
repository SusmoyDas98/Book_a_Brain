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
        $tutor = Tutor::where('userId', $user->userId)->first();
        $guardian = Guardian::where('userId', $user->userId)->first();
        $tutorProfile = TutorProfile::where('tutorId', $user->userId)->first();
        $verificationDocuments = VerificationDocument::where('tutorId', $user->userId)->get();

        $completionPercentage = 0;

        if ($user->role === 'TUTOR') {
            $fields = [
                $user->name,
                $user->email,
                $user->phone,
                optional($tutor)->bio,
                optional($tutor)->subjects,
                optional($tutor)->classesTaught,
                optional($tutor)->medium,
                optional($tutor)->teachingMode,
                optional($tutor)->expectedSalary,
                optional($tutor)->availability,
                optional($tutor)->cvPath,
                optional($tutorProfile)->institution,
                optional($tutorProfile)->degree,
                optional($tutorProfile)->passingYear,
                optional($tutorProfile)->result,
                optional($tutorProfile)->experience,
                optional($tutorProfile)->currentOccupation
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
        $tutor = Tutor::where('userId', $user->userId)->first();
        $guardian = Guardian::where('userId', $user->userId)->first();
        $tutorProfile = TutorProfile::where('tutorId', $user->userId)->first();
        $verificationDocuments = VerificationDocument::where('tutorId', $user->userId)->get()->keyBy('docType');

        return view('profile.edit', compact('user', 'tutor', 'guardian', 'tutorProfile', 'verificationDocuments'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'cv' => 'nullable|mimes:pdf|max:2048',
            'nid_document' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'occupation_document' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        if ($user->role === 'TUTOR') {
            $tutor = Tutor::where('userId', $user->userId)->first();

            if ($tutor) {
                $tutor->bio = $request->bio;
                $tutor->subjects = $request->subjects;
                $tutor->classesTaught = $request->classesTaught;
                $tutor->medium = $request->medium;
                $tutor->teachingMode = $request->teachingMode;
                $tutor->expectedSalary = $request->expectedSalary;
                $tutor->availability = $request->availability;

                if ($request->hasFile('cv')) {
                    $cvPath = $request->file('cv')->store('cv_uploads', 'public');
                    $tutor->cvPath = $cvPath;
                }

                $tutor->save();
            }

            $tutorProfile = TutorProfile::firstOrNew(['tutorId' => $user->userId]);
            $tutorProfile->institution = $request->institution;
            $tutorProfile->degree = $request->degree;
            $tutorProfile->passingYear = $request->passingYear;
            $tutorProfile->result = $request->result;
            $tutorProfile->experience = $request->experience;
            $tutorProfile->currentOccupation = $request->currentOccupation;

            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cv_uploads', 'public');
                $tutorProfile->cvFilePath = $cvPath;
            }

            $tutorProfile->save();

            if ($request->hasFile('nid_document')) {
                $nidPath = $request->file('nid_document')->store('verification_documents', 'public');

                VerificationDocument::updateOrCreate(
                    ['tutorId' => $user->userId, 'docType' => 'NID'],
                    [
                        'filePath' => $nidPath,
                        'status' => 'PENDING',
                        'reviewNote' => null
                    ]
                );
            }

            if ($request->hasFile('occupation_document')) {
                $occupationPath = $request->file('occupation_document')->store('verification_documents', 'public');

                VerificationDocument::updateOrCreate(
                    ['tutorId' => $user->userId, 'docType' => 'OCCUPATION_CARD'],
                    [
                        'filePath' => $occupationPath,
                        'status' => 'PENDING',
                        'reviewNote' => null
                    ]
                );
            }
        }

        if ($user->role === 'GUARDIAN') {
            $guardian = Guardian::where('userId', $user->userId)->first();

            if ($guardian) {
                $guardian->address = $request->address;
                $guardian->latitude = $request->latitude;
                $guardian->longitude = $request->longitude;
                $guardian->numberOfChildren = $request->numberOfChildren;
                $guardian->preferredSubjects = $request->preferredSubjects;
                $guardian->save();
            }
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
}