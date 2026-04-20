<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HireConfirmation extends Model
{
    protected $fillable = [
        'job_id',
        'application_id',
        'guardian_id',
        'tutor_id',
        'guardian_confirmed',
        'tutor_confirmed',
        'guardian_confirmed_at',
        'tutor_confirmed_at',
        'status',
        'cancellation_reason',
        'cancelled_by',
        'cancelled_at',
    ];

    protected $casts = [
        'guardian_confirmed'    => 'boolean',
        'tutor_confirmed'       => 'boolean',
        'guardian_confirmed_at' => 'datetime',
        'tutor_confirmed_at'    => 'datetime',
        'cancelled_at'          => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(JobPost::class, 'job_id');
    }

    public function application()
    {
        return $this->belongsTo(JobPostResponse::class, 'application_id');
    }

    public function isBothConfirmed(): bool
    {
        return $this->guardian_confirmed && $this->tutor_confirmed;
    }

    public function isFullyCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
