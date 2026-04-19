<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TuitionPayment extends Model
{
    protected $table = 'tuition_payments';

    protected $fillable = [
        'transaction_id',
        'guardian_id',
        'tutor_id',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'payment_date',
        'month_label',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Scope: records for a specific guardian.
     */
    public function scopeForGuardian($query, $guardianId)
    {
        return $query->where('guardian_id', $guardianId);
    }

    /**
     * Scope: records for a specific tutor.
     */
    public function scopeForTutor($query, $tutorId)
    {
        return $query->where('tutor_id', $tutorId);
    }

    /**
     * Scope: admin-safe select — amount and currency deliberately excluded.
     */
    public function scopeAdminSafe($query)
    {
        return $query->select([
            'id',
            'transaction_id',
            'guardian_id',
            'tutor_id',
            'payment_method',
            'payment_status',
            'payment_date',
            'month_label',
            'description',
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
