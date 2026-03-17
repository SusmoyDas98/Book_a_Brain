<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TuitionContract extends Model
{
    protected $fillable = [
        'guardian_id',
        'tutor_id',
        'subject',
        'schedule',
        'salary',
        'start_date',
        'status',
        'guardian_notes',
        'tutor_notes',
        'ended_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'ended_at'   => 'datetime',
    ];

    public function guardian()
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function sessionLogs()
    {
        return $this->hasMany(SessionLog::class, 'contract_id')->orderBy('week_start', 'desc');
    }

    // Is the contract past month 1?
    public function isPastFirstMonth(): bool
    {
        return now()->greaterThan($this->start_date->addMonth());
    }
}