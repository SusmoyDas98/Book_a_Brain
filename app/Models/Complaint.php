<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    protected $fillable = [
        'filed_by',
        'against_user',
        'contract_id',
        'subject',
        'description',
        'status',
        'admin_note',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function filer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'filed_by');
    }

    public function accusedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'against_user');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(TuitionContract::class, 'contract_id');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
