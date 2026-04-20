<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'attachment_path',
        'attachment_type',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Check if this message has a file attachment.
     */
    public function hasAttachment(): bool
    {
        return ! empty($this->attachment_path);
    }

    /**
     * Check if the attachment is an image.
     */
    public function isImageAttachment(): bool
    {
        return $this->hasAttachment() && $this->attachment_type === 'image';
    }
}
