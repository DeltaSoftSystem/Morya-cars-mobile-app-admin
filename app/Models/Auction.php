<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
    'car_listing_id', 'created_by', 'start_at', 'end_at', 'base_price', 
    'bid_increment', 'status', 'winner_id', 'final_price'
]   ;


    protected $casts = [
    'start_at' => 'datetime',
    'end_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(AppUser::class);
    }

    public function carListing()
    {
        return $this->belongsTo(CarListing::class, 'car_listing_id');
    }


    public function bids()
    {
    return $this->hasMany(AuctionBid::class);
    }


    public function winner()
    {
    return $this->belongsTo(AppUser::class, 'winner_id');
    }

    public function seller()
    {
        return $this->hasOneThrough(
            AppUser::class,      // Final model to reach
            CarListing::class,   // Intermediate model
            'id',                // car_listings.id
            'id',                // users.id
            'car_listing_id',    // auctions.car_listing_id
            'user_id'            // car_listings.user_id (seller)
        );
    }

    public function result()
    {
        return $this->hasOne(AuctionResult::class, 'auction_id');
    }
    public function resultWinner()
    {
        return $this->belongsTo(AppUser::class, 'winner_user_id');
    }
}
