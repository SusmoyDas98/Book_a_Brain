<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $table = 'tutors';

    // Primary key
    protected $primaryKey = 'tutor_id';

    // Because tutor_id comes from users table
    public $incrementing = false;

    // Primary key type
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'bio',
        'subjects',
        'classes_taught',
        'medium',
        'teaching_mode',
        'expected_salary',
        'availability',
        'cv_path',
        'is_verified',
        'verification_badge',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function profile()
    {
        // One tutor has one profile
        return $this->hasOne(TutorProfile::class, 'tutor_id', 'id');
    }

    public function tuitionEngagements()
    {
        return $this->hasMany(TuitionPayment::class, 'tutor_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'subscriber_id')
            ->where('subscriber_type', 'tutor');
    }

    public function subscriptionPayments()
    {
        return $this->hasMany(SubscriptionPayment::class, 'subscriber_id')
            ->where('subscriber_type', 'tutor');
    }
}
