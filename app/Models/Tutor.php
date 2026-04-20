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
        'gender',
        'nid_card',
        'cv_pdf',
        'student_id_card',
        'total_earning',
        'ratings',
        'review',
    ];

    // Relationship with User
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        // tutor_id is the PK of the tutors table (= user_id)
        return $this->hasOne(TutorProfile::class, 'tutor_id', 'tutor_id');
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

    public function bkashAccounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BkashAccount::class, 'account_holder_id')
            ->where('account_holder_type', 'tutor');
    }
}
