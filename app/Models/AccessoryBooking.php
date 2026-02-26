<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'accessory_id',
        'quantity',
        'unit_price',
        'total_amount',
        'name',
        'mobile',
        'email',
        'address',
        'status'
    ];

    public function accessory()
    {
        return $this->belongsTo(Accessory::class);
    }
}
