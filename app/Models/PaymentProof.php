<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    use HasFactory;
    protected $fillable = [
            'booking_id',
            'file_path',
            'utr_number',
            'amount',
            'payment_date',
            'status'
        ];

        public function booking()
        {
            return $this->belongsTo(Booking::class);
        }

}
