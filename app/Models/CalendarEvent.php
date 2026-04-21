<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = ['tutor_id', 'title', 'event_date', 'start_time', 'end_time', 'notes'];

    protected $casts = ['event_date' => 'date'];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id', 'tutor_id');
    }
}
