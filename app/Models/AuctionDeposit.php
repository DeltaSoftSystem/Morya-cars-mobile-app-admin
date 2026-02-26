<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id','user_id','deposit_amount','payment_proof','status','admin_comment'
    ];

    public function auction(){ return $this->belongsTo(Auction::class); }
    public function user(){ return $this->belongsTo(AppUser::class); }
}
