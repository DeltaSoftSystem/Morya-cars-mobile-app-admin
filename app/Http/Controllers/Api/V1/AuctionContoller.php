<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\AuctionBid;

class AuctionContoller extends Controller
{
     // 1️⃣ List active auctions
    public function index()
    {
        $auctions = Auction::with('carListing')
            ->whereHas('carListing', function($q){
                $q->whereIn('auction_status',['approved','scheduled']);
            })
            ->orderBy('start_at','asc')
            ->get();
        
        return response()->json([
            'status' => true,
            'data' => $auctions
        ]);
    }

    // 2️⃣ Show auction details
    public function show($id)
    {
        $auction = Auction::with(['carListing','bids.user'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $auction
        ]);
    }

    // 3️⃣ Place a bid
    public function placeBid(Request $request, $id)
    {
        $auction = Auction::findOrFail($id);

        // Check if auction is running
        if($auction->car->auction_status != 'scheduled' || now()->lt($auction->start_at) || now()->gt($auction->end_at)){
            return response()->json(['status'=>false, 'message'=>'Auction not active'], 400);
        }

        $request->validate([
            'amount' => 'required|numeric|min:'.$auction->bid_increment,
        ]);

        // Check if bid is higher than last bid or base_price
        $lastBid = $auction->bids()->latest('amount')->first();
        $minBid = $lastBid ? $lastBid->amount + $auction->bid_increment : $auction->base_price;

        if($request->amount < $minBid){
            return response()->json(['status'=>false, 'message'=>"Bid must be at least ₹{$minBid}"], 400);
        }

        $bid = AuctionBid::create([
            'auction_id' => $auction->id,
            'user_id' => $request->user()->id,
            'amount' => $request->amount
        ]);

        return response()->json(['status'=>true,'message'=>'Bid placed successfully','data'=>$bid]);
    }

    // 4️⃣ User's won auctions
    public function wonAuctions(Request $request)
    {
        $auctions = Auction::with('carListing')
            ->where('winner_id', $request->user()->id)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $auctions
        ]);
    }

    
}
