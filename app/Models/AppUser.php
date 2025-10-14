<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    use HasFactory;

     protected $table = 'app_users';

   protected $fillable = [
        'name',
        'email',
        'mobile',
        'role',
        'status',
        'otp',
        'is_mobile_verified',
        'is_email_verified'
    ];
}
