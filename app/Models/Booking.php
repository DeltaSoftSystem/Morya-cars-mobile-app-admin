<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'car_listing_id',
        'booking_amount',
        'payment_mode',
        'booking_status',
        'payment_status',
        'admin_comment'
    ];

    public function user()
    {
        return $this->belongsTo(AppUser::class, 'user_id');
    }

    public function carListing()
    {
        return $this->belongsTo(CarListing::class, 'car_listing_id');
    }

    public function paymentProofs()
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function sellerPayments()
    {
        return $this->hasMany(SellerPayment::class);
    }

}
