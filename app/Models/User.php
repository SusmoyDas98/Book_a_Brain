<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'google_token',
        'google_refresh_token',
        'avatar',
        'role',
    ];

    /**
     * Hidden fields for serialization
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function tutorProfile()
    {
        return $this->hasOne(TutorProfile::class, 'tutor_id');
    }

    public function guardian()
    {
        return $this->hasOne(Guardian::class, 'guardian_id');
    }

    /**
     * All chat conversations this user is part of (as guardian or tutor).
     */
    public function conversations()
    {
        return Conversation::where('guardian_id', $this->id)
            ->orWhere('tutor_id', $this->id);
    }

    /**
     * Reviews given by this user (as guardian).
     */
    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'guardian_id');
    }

    /**
     * Reviews received by this user (as tutor).
     */
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'tutor_id');
    }
}
