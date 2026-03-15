<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationDocument extends Model
{
    protected $primaryKey = 'docId';

    protected $fillable = [
        'tutorId',
        'docType',
        'filePath',
        'status',
        'reviewedBy',
        'reviewNote'
    ];
}