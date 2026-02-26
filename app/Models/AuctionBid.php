<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionBid extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id','user_id','masked_name','amount','bid_at'
    ];

    public function user()
    {
        return $this->belongsTo(AppUser::class, 'user_id'); // winner bidder / bid user
    }
}
