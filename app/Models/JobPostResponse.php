<?php

namespace App\Models;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPostResponse extends Model
{
    use HasFactory;

    protected $table = 'job_post_responses';

    const STATUS_PENDING     = 'Pending';
    const STATUS_SHORTLISTED = 'Shortlisted';
    const STATUS_HIRED       = 'Hired';
    const STATUS_CONFIRMED   = 'Confirmed';
    const STATUS_DISCARDED   = 'Discarded';
    const STATUS_REJECTED    = 'Rejected';
    const STATUS_DECLINED    = 'Declined';

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
        'job_post_id',
        'application_message',
        'status',
    ];

    protected $casts = [
        'tutor_educational_institutions' => 'array',
        'tutor_work_experience'          => 'array',
        'teaching_method'                => 'array',
        'availability'                   => 'array',
        'preferred_mediums'              => 'array',
        'preferred_subjects'             => 'array',
        'preferred_classes'              => 'array',
        'shortlisted'                    => 'boolean',
        'expected_salary'                => 'decimal:2',
        'tutor_rating'                   => 'float',
        'job_post_id'                    => 'integer',
    ];

    public function tutorProfile()
    {
        return $this->belongsTo(TutorProfile::class, 'tutor_id', 'tutor_id');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id', 'id');
    }

    public function hireConfirmation()
    {
        return $this->hasOne(HireConfirmation::class, 'application_id');
    }
}
