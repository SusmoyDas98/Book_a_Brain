<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $fillable = [
        'guardian_id',
        'name',
        'email',
        'contact_no',
        'gender',
        'profile_picture',
        'nid_card',
        'address',
        'location',
    ];

    protected $casts = [
        'location' => 'array',
    ];

    public function tuitionPayments()
    {
        return $this->hasMany(TuitionPayment::class, 'guardian_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'subscriber_id')
            ->where('subscriber_type', 'guardian');
    }

    public function subscriptionPayments()
    {
        return $this->hasMany(SubscriptionPayment::class, 'subscriber_id')
            ->where('subscriber_type', 'guardian');
    }

    public function bkashAccounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BkashAccount::class, 'account_holder_id')
            ->where('account_holder_type', 'guardian');
    }

    public function hireConfirmations()
    {
        return $this->hasMany(HireConfirmation::class, 'guardian_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'guardian_id');
    }

    public function notifications()
    {
        return $this->hasMany(AppNotification::class, 'recipient_id')
                    ->where('recipient_type', 'guardian');
    }
}
