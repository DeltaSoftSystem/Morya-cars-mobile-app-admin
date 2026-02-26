<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $table = 'service_requests';

    protected $fillable = [
        'user_id',
        'service_id',
        'car_id',
        'name',
        'mobile',
        'email',
        'city',
        'preferred_date',
        'preferred_time',
        'description',
        'status',
        'admin_comment'
    ];

    /* ================= RELATIONS ================= */

    // Mobile app user
    public function user()
    {
        return $this->belongsTo(AppUser::class, 'user_id');
    }

    // Service master
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function item()
    {
        return $this->belongsTo(ServiceItem::class, 'service_item_id');
    }


    // Optional car
    public function car()
    {
        return $this->belongsTo(CarListing::class, 'id');
    }
}
