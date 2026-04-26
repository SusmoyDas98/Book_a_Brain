<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TutorEvent extends Model
{
    protected $fillable = [
        'tutor_id',
        'contract_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'type',
        'color',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(TuitionContract::class, 'contract_id');
    }
}
