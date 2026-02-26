<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AppUser extends Authenticatable
{
    use HasApiTokens, HasFactory,Notifiable;

     protected $table = 'app_users';

   protected $fillable = [
        'name',
        'email',
        'mobile',
        'role',
        'last_login_ip',
        'status',
        'otp',
        'is_mobile_verified',
        'is_email_verified'
    ];

    protected $casts = [
        'is_mobile_verified' => 'boolean',
        'is_email_verified' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(AppUser::class, 'seller_id');
    }

     public function dealerProfile()
    {
        return $this->hasOne(DealerProfile::class, 'user_id');
    }

    public function dealerKycDocuments()
    {
        return $this->hasMany(DealerKycDocument::class, 'user_id');
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'user_id');
    }
}
