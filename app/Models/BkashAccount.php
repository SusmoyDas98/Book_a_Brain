<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkashAccount extends Model
{
    protected $table = 'bkash_accounts';

    protected $fillable = [
        'account_holder_type',
        'account_holder_id',
        'phone_number',
        'otp',
        'password',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeForGuardian($query, int $id): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('account_holder_type', 'guardian')
            ->where('account_holder_id', $id);
    }

    public function scopeForTutor($query, int $id): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('account_holder_type', 'tutor')
            ->where('account_holder_id', $id);
    }
}
