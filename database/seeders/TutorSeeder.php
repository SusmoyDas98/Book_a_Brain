<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TutorSeeder extends Seeder
{
    public function run(): void
    {
        $tutors = [
            [
                'tutor_id' => 1,
                'gender' => 'Male',
                'nid_card' => 'storage/nid/nid1.jpg',
                'cv_pdf' => 'storage/cv/cv1.pdf',
                'student_id_card' => 'storage/student_id/std1.jpg',
                'total_earning' => 1200.50,
                'ratings' => 4.5,
                'review' => 'Very patient and explains concepts clearly.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tutor_id' => 2,
                'gender' => 'Female',
                'nid_card' => 'storage/nid/nid2.jpg',
                'cv_pdf' => 'storage/cv/cv2.pdf',
                'student_id_card' => 'storage/student_id/std2.jpg',
                'total_earning' => 2450.00,
                'ratings' => 4.2,
                'review' => 'Strong background in mathematics.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tutor_id' => 3,
                'gender' => 'Male',
                'nid_card' => 'storage/nid/nid3.jpg',
                'cv_pdf' => 'storage/cv/cv3.pdf',
                'student_id_card' => 'storage/student_id/std3.jpg',
                'total_earning' => 980.75,
                'ratings' => 4.0,
                'review' => 'Good at explaining programming topics.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($tutors as $tutor) {
            DB::table('tutors')->updateOrInsert(
                ['tutor_id' => $tutor['tutor_id']],
                $tutor
            );
        }
    }
}
