<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id','status','base_price','bid_increment',
        'current_bid','winner_user_id','winner_name',
        'start_at','end_at','started_at','last_bid_at'
    ];

    public function bids()
    {
        return $this->hasMany(AuctionBid::class, 'auction_id', 'auction_id');
    }

    public function winnerUser()
    {
        return $this->belongsTo(AppUser::class, 'winner_user_id');
    }
}
