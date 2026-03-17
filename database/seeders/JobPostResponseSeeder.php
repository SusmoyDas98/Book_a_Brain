<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPostResponse;
use App\Models\TutorProfile;
use App\Models\Tutor;

class JobPostResponseSeeder extends Seeder
{
    public function run(): void
    {
        // Get all tutors and tutor profiles
        $tutorProfiles = TutorProfile::all();
        $tutors = Tutor::all()->keyBy('tutor_id'); // key by tutor_id for easy lookup

        foreach ($tutorProfiles as $profile) {
            // Get corresponding tutor rating
            $rating = $tutors[$profile->tutor_id]->ratings ?? null;
            $gender = $tutors[$profile->tutor_id]->gender ?? "not specified";

            JobPostResponse::create([
                'guardian_id' => rand(1, 50), // random guardian id
                'tutor_id' => $profile->tutor_id,
                'tutor_profile_pic' => $profile->profile_picture,
                'tutor_name' => $profile->name,
                'gender' => $gender,
                'cv' => $profile->cv,
                'tutor_educational_institutions' => $profile->educational_institutions,
                'tutor_work_experience' => $profile->work_experience,
                'teaching_method' => $profile->teaching_method,
                'availability' => $profile->availability,
                'preferred_mediums' => $profile->preferred_mediums,
                'preferred_subjects' => $profile->preferred_subjects,
                'preferred_classes' => $profile->preferred_classes,
                'expected_salary' => $profile->expected_salary,
                'tutor_rating' => $rating,
                'shortlisted' => rand(0,1), // random true/false
            ]);
        }

        $this->command->info('JobPostResponse table seeded successfully!');
    }
}