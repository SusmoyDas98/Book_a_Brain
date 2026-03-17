<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPostResponse extends Model
{
    use HasFactory;

    protected $table = 'job_post_responses';

    protected $fillable = [
        'guardian_id',
        'tutor_id',
        'tutor_profile_pic',
        'tutor_name',
        'gender',
        'cv',
        'tutor_educational_institutions',
        'tutor_work_experience',
        'teaching_method',
        'availability',
        'preferred_mediums',
        'preferred_subjects',
        'preferred_classes',
        'expected_salary',
        'tutor_rating',
        'shortlisted',
    ];

    // Cast JSON fields to arrays
    protected $casts = [
        'tutor_educational_institutions' => 'array',
        'tutor_work_experience' => 'array',
        'teaching_method' => 'array',
        'availability' => 'array',
        'preferred_mediums' => 'array',
        'preferred_subjects' => 'array',
        'preferred_classes' => 'array',
        'shortlisted' => 'boolean',
        'expected_salary' => 'decimal:2',
        'tutor_rating' => 'float',
    ];

    // Relationship to TutorProfile (optional)
    public function tutorProfile()
    {
        return $this->belongsTo(TutorProfile::class, 'tutor_id', 'tutor_id');
    }
}