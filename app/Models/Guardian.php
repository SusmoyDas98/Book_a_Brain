<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $fillable = [
        'user_id',
        'name',        
        'email',       
        'contact_no',  
        'gender',
        'profile_picture',
        'nid_card',    
        'address',
        'location',
    ];
    protected $casts = [
        'location' => 'array',
    ];
}