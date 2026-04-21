<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $table = 'tutors';

    protected $primaryKey = 'tutor_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'tutor_id',
        'gender',
        'nid_card',
        'cv_pdf',
        'student_id_card',
        'total_earning',
        'ratings',
        'review',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
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

    public function hireConfirmations()
    {
        return $this->hasMany(HireConfirmation::class, 'tutor_id', 'tutor_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'tutor_id', 'tutor_id');
    }

    public function notifications()
    {
        return $this->hasMany(AppNotification::class, 'recipient_id', 'tutor_id')
            ->where('recipient_type', 'tutor');
    }
}
