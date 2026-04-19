<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'transaction_id',
        'subscriber_type',
        'subscriber_id',
        'plan_name',
        'subscription_amount',
        'currency',
        'billing_cycle',
        'payment_method',
        'status',
        'started_at',
        'expires_at',
    ];

    protected $casts = [
        'subscription_amount' => 'decimal:2',
        'started_at' => 'date',
        'expires_at' => 'date',
    ];

    /**
     * Scope: subscriptions for a specific guardian.
     */
    public function scopeForGuardian($query, $id)
    {
        return $query->where('subscriber_type', 'guardian')->where('subscriber_id', $id);
    }

    /**
     * Scope: subscriptions for a specific tutor.
     */
    public function scopeForTutor($query, $id)
    {
        return $query->where('subscriber_type', 'tutor')->where('subscriber_id', $id);
    }

    /**
     * Scope: admin-safe select — subscription_amount and currency deliberately excluded.
     */
    public function scopeAdminSafe($query)
    {
        return $query->select([
            'id',
            'subscriber_type',
            'subscriber_id',
            'plan_name',
            'billing_cycle',
            'status',
            'started_at',
            'expires_at',
            'transaction_id',
        ]);
    }

    /**
     * Returns true only when the subscription is active and not yet expired.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at->isFuture();
    }

    /**
     * Accessor: human-readable payment method.
     */
    public function getFormattedMethodAttribute(): string
    {
        return 'Book-a-Brain Portal';
    }
}
