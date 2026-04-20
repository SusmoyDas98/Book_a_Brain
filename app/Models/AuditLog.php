<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'event_type',
        'job_id',
        'application_id',
        'guardian_id',
        'tutor_id',
        'performed_by',
        'performed_role',
        'payment_amount',
        'payment_status',
        'payment_ref_id',
        'notes',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
    ];

    public function scopeAdminSafe($query)
    {
        return $query->select([
            'id', 'event_type', 'job_id', 'application_id', 'guardian_id',
            'tutor_id', 'performed_by', 'performed_role', 'payment_status',
            'payment_ref_id', 'notes', 'created_at',
        ]);
    }

    public function scopeForGuardian($query, $guardianId)
    {
        return $query->where('guardian_id', $guardianId);
    }

    public function scopeForTutor($query, $tutorId)
    {
        return $query->where('tutor_id', $tutorId);
    }
}
