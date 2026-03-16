<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $fillable = [
        'user_id',
        'gender',
        'profile_picture',
        'address',
        'latitude',
        'longitude',
        'number_of_children',
        'preferred_subjects'  
    ];
}