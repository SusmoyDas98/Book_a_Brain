<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    protected $table = 'job_posts';

    const STATUS_OPEN = 'Open';

    const STATUS_SHORTLISTING = 'Shortlisting';

    const STATUS_HIRED = 'Hired';

    const STATUS_ONLINE = 'Online';

    const STATUS_COMPLETED = 'Completed';

    const STATUS_CANCELLED = 'Cancelled';

    protected $fillable = [
        'guardian_id',
        'title',
        'subject',
        'class_level',
        'expected_salary',
        'location',
        'medium',
        'mode',
        'description',
        'status',
        'shortlisted_count',
    ];

    protected $casts = [
        'expected_salary' => 'decimal:2',
        'shortlisted_count' => 'integer',
    ];

    public function guardian()
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    public function responses()
    {
        return $this->hasMany(JobPostResponse::class, 'job_post_id');
    }

    public function hireConfirmation()
    {
        return $this->hasOne(HireConfirmation::class, 'job_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'job_id');
    }

    public function isShortlistFull(): bool
    {
        return $this->shortlisted_count >= 5;
    }

    public function canApply(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }
}
