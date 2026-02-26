<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPayment extends Model
{
    use HasFactory;

     protected $fillable = [
        'booking_id',
        'seller_id',
        'amount',
        'payment_mode',
        'transaction_ref',
        'payment_date',
        'proof_file',
        'status',
        'admin_comment',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
