<?php

namespace Database\Seeders;

use App\Models\TutorProfile;
use Illuminate\Database\Seeder;

class TutorProfileSeeder extends Seeder
{
    public function run(): void
    {
        TutorProfile::firstOrCreate(['tutor_id' => 1], [
            'profile_picture' => 'storage/profiles/tutor1.jpg',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'contact_no' => '01712345678',
            'cv' => 'storage/cvs/john.pdf',
            'educational_institutions' => [
                'school' => 'ABC High School',
                'college' => 'DEF College',
                'university' => 'BRAC University',
            ],
            'work_experience' => [
                '2018-2020' => 'Math Teacher at XYZ School',
                '2020-2023' => 'Physics Tutor at ABC Academy',
            ],
            'teaching_method' => ['online', 'offline', 'group classes'],
            'availability' => ['Mon-Fri 5-8pm', 'Sat 10am-2pm'],
            'preferred_mediums' => ['English', 'Bangla'],
            'preferred_subjects' => ['Math', 'Physics', 'Programming'],
            'preferred_classes' => ['10', '2'],
            'expected_salary' => 2500.00,
        ]);

        TutorProfile::firstOrCreate(['tutor_id' => 2], [
            'profile_picture' => 'storage/profiles/tutor2.jpg',
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'contact_no' => '01812345678',
            'cv' => 'storage/cvs/jane.pdf',
            'educational_institutions' => [
                'school' => 'XYZ High School',
                'college' => 'LMN College',
                'university' => 'North South University',
            ],
            'work_experience' => [
                '2017-2019' => 'English Teacher at LMN School',
                '2019-2023' => 'Online Tutor at EdTech Platform',
            ],
            'teaching_method' => ['online', 'one-to-one'],
            'availability' => ['Tue-Thu 4-7pm'],
            'preferred_mediums' => ['English'],
            'preferred_subjects' => ['English', 'Literature'],
            'preferred_classes' => ['4', '3'],
            'expected_salary' => 2000.00,
        ]);

        TutorProfile::firstOrCreate(['tutor_id' => 3], [
            'profile_picture' => 'storage/profiles/tutor3.jpg',
            'name' => 'Alex Johnson',
            'email' => 'alex@example.com',
            'contact_no' => '01987654321',
            'cv' => 'storage/cvs/alex.pdf',
            'educational_institutions' => [
                'school' => 'DEF High School',
                'college' => 'GHI College',
                'university' => 'University of Dhaka',
            ],
            'work_experience' => [
                '2015-2018' => 'Science Tutor at ABC Academy',
                '2018-2023' => 'Freelance Programming Tutor',
            ],
            'teaching_method' => ['offline', 'group classes'],
            'availability' => ['Mon-Fri 6-9pm', 'Sat 9am-1pm'],
            'preferred_mediums' => ['Bangla'],
            'preferred_subjects' => ['Math', 'Computer Science'],
            'preferred_classes' => ['12'],
            'expected_salary' => 1800.00,
        ]);
    }
}
