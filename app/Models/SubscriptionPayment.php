<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    protected $table = 'subscription_payments';

    protected $fillable = [
        'transaction_id',
        'subscriber_type',
        'subscriber_id',
        'subscription_id',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'payment_date',
        'billing_period',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Scope: records for a specific guardian.
     */
    public function scopeForGuardian($query, $id)
    {
        return $query->where('subscriber_type', 'guardian')->where('subscriber_id', $id);
    }

    /**
     * Scope: records for a specific tutor.
     */
    public function scopeForTutor($query, $id)
    {
        return $query->where('subscriber_type', 'tutor')->where('subscriber_id', $id);
    }

    /**
     * Scope: admin-safe select — amount and currency deliberately excluded.
     */
    public function scopeAdminSafe($query)
    {
        return $query->select([
            'id',
            'transaction_id',
            'subscriber_type',
            'subscriber_id',
            'subscription_id',
            'payment_method',
            'payment_status',
            'payment_date',
            'billing_period',
        ]);
    }

    /**
     * Accessor: human-readable payment method.
     */
    public function getFormattedMethodAttribute(): string
    {
        return 'Book-a-Brain Portal';
    }
}
