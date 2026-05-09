<?php

namespace Database\Seeders;

use App\Models\Guardian;
use App\Models\JobPost;
use Illuminate\Database\Seeder;

class JobPostSeeder extends Seeder
{
    public function run(): void
    {
        // Get all guardians
        $guardians = Guardian::all();

        // If there are no guardians, stop seeding
        if ($guardians->isEmpty()) {
            $this->command->info('No guardians found. Please seed guardians first.');
            return;
        }

        // Sample data arrays
        $titles = [
            'Need a Math Tutor',
            'Looking for an English Tutor',
            'Physics Tutor Required',
            'Chemistry Tutor Needed',
            'Biology Tutor Wanted',
            'ICT Tutor Needed',
            'Bangla Tutor Required',
            'Accounting Tutor Needed',
        ];

        $subjects = [
            'Mathematics',
            'English',
            'Physics',
            'Chemistry',
            'Biology',
            'ICT',
            'Bangla',
            'Accounting',
        ];

        $classLevels = [
            'Class 5',
            'Class 8',
            'SSC',
            'HSC',
            'O Level',
            'A Level',
        ];

        $locations = [
            'Dhanmondi, Dhaka',
            'Gulshan, Dhaka',
            'Mirpur, Dhaka',
            'Uttara, Dhaka',
            'Banani, Dhaka',
            'Mohammadpur, Dhaka',
            'Bashundhara, Dhaka',
            'Rampura, Dhaka',
        ];

        $mediums = ['Bangla', 'English', 'Both'];
        $modes = ['Online', 'Offline', 'Both'];
        $statuses = ['Open', 'Shortlisting', 'Hired', 'Closed'];

        // Create one or more job posts for each guardian
        foreach ($guardians as $guardian) {
            // Each guardian will have between 1 and 3 job posts
            $numberOfPosts = rand(1, 3);

            for ($i = 0; $i < $numberOfPosts; $i++) {
                // Pick a random subject and matching title
                $index = array_rand($subjects);

                JobPost::create([
                    'guardian_id' => $guardian->guardian_id,
                    // If guardian_id does not work, replace with:
                    // 'guardian_id' => $guardian->id,

                    'title' => $titles[$index],
                    'subject' => $subjects[$index],
                    'class_level' => $classLevels[array_rand($classLevels)],
                    'expected_salary' => rand(3000, 15000),
                    'location' => $locations[array_rand($locations)],
                    'medium' => $mediums[array_rand($mediums)],
                    'mode' => $modes[array_rand($modes)],
                    'description' =>
                        'Looking for an experienced tutor to teach ' .
                        $subjects[$index] .
                        ' to my child. Preferred candidates should be punctual and have strong teaching skills.',
                    'status' => $statuses[array_rand($statuses)],
                    'shortlisted_count' => rand(0, 5),
                ]);
            }
        }

        $this->command->info('JobPost table seeded successfully!');
    }
}