<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    protected $table = 'tutor_profiles';

    // Primary key
    protected $primaryKey = 'tutor_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'tutor_id',
        'profile_picture',
        'name',
        'email',
        'contact_no',
        'cv',
        'educational_institutions',
        'work_experience',
        'teaching_method',
        'availability',
        'preferred_mediums',
        'preferred_subjects',
        'expected_salary'
    ];

    // Cast JSON columns to PHP array automatically
    protected $casts = [
        'educational_institutions' => 'array',
        'work_experience' => 'array',
        'teaching_method' => 'array',
        'availability' => 'array',
        'preferred_mediums' => 'array',
        'preferred_subjects' => 'array',
    ];

    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
    public function tutor()
    {
        // One profile belongs to one tutor
        return $this->belongsTo(Tutor::class, 'tutor_id', 'id');
    }    
}