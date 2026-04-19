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
}
