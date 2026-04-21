<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionLog extends Model
{
    protected $fillable = [
        'contract_id',
        'week_start',
        'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun',
        'tutor_note',
        'guardian_note',
    ];

    protected $casts = [
        'week_start' => 'date',
        'mon' => 'boolean',
        'tue' => 'boolean',
        'wed' => 'boolean',
        'thu' => 'boolean',
        'fri' => 'boolean',
        'sat' => 'boolean',
        'sun' => 'boolean',
    ];

    public function contract()
    {
        return $this->belongsTo(TuitionContract::class, 'contract_id');
    }

    public function sessionCount(): int
    {
        return collect(['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'])
            ->filter(fn ($d) => $this->$d)
            ->count();
    }
}
