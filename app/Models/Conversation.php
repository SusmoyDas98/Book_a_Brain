<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'guardian_id',
        'tutor_id',
        'contract_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(TuitionContract::class, 'contract_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Scope: conversations where the given user is a participant.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('guardian_id', $userId)
            ->orWhere('tutor_id', $userId);
    }

    /**
     * Get the other participant relative to the given user.
     */
    public function otherUser(int $userId): ?User
    {
        if ($this->guardian_id === $userId) {
            return $this->tutor;
        }

        return $this->guardian;
    }

    /**
     * Count unread messages for a given user.
     */
    public function unreadCountFor(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->count();
    }
}
