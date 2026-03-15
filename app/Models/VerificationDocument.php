<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationDocument extends Model
{
    protected $fillable = [
        'tutor_id',
        'doc_type',
        'file_path',
        'status',
        'reviewed_by',
        'review_note',
        'uploaded_at'
    ];
}