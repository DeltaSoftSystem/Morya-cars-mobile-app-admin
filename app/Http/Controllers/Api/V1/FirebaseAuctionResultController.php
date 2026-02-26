<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuctionResult;
use App\Models\AuctionBid;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;
use App\Models\AppUser;
use App\Models\Auction;

class FirebaseAuctionResultController extends Controller
{
    public function store(Request $request)
    {
        // 🔐 Secure via header key
         if ($request->header('X-FIREBASE-KEY') !== 'morya_firebase_sync_2025') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $data = $request->validate([
            'auction.auction_id' => 'required|integer',
            'auction.status' => 'required|string',
            'auction.base_price' => 'required|numeric',
            'auction.bid_increment' => 'required|numeric',
            'auction.current_bid' => 'nullable|numeric',
            'auction.winner_user_id' => 'nullable|integer',
            'auction.winner_name' => 'nullable|string',
            'auction.start_at' => 'required|date',
            'auction.end_at' => 'required|date',
            'auction.started_at' => 'nullable|date',
            'auction.last_bid_at' => 'nullable|date',
            'bids' => 'required|array'
        ]);

        DB::transaction(function () use ($data) {

            /** ----------------------
             * 1️⃣ Store auction result
             * ---------------------- */
            AuctionResult::updateOrCreate(
                ['auction_id' => $data['auction']['auction_id']],
                [
                    'status' => $data['auction']['status'],
                    'base_price' => $data['auction']['base_price'],
                    'bid_increment' => $data['auction']['bid_increment'],
                    'current_bid' => $data['auction']['current_bid'],
                    'winner_user_id' => $data['auction']['winner_user_id'],
                    'winner_name' => $data['auction']['winner_name'],
                    'start_at' => $data['auction']['start_at'],
                    'end_at' => $data['auction']['end_at'],
                    'started_at' => $data['auction']['started_at'] ?? null,
                    'last_bid_at' => $data['auction']['last_bid_at'] ?? null,
                ]
            );

            /** ----------------------
             * 2️⃣ Store bid history
             * ---------------------- */
            foreach ($data['bids'] as $bid) {
                AuctionBid::updateOrCreate(
                    [
                        'auction_id' => $data['auction']['auction_id'],
                        'user_id' => $bid['user_id'],
                        'amount' => $bid['amount'],
                        'bid_at' => $bid['bid_at'],
                    ],
                    [
                        'masked_name' => $bid['masked_name'],
                    ]
                );
            }
        });

        /**
         * 📧 SEND BID WON EMAIL (AFTER TRANSACTION)
         */
        $winnerUserId = $data['auction']['winner_user_id'] ?? null;

        if ($winnerUserId) {

            $winner = AppUser::find($winnerUserId);

            if ($winner && !empty($winner->email)) {

                $auction = Auction::with('carListing')
                    ->find($data['auction']['auction_id']);

                NotificationService::sendEmail(
                    $winner->email,
                    'Congratulations! You Won the Auction',
                    [
                        'name'    => $winner->name,
                        'message' => "You have won the auction for '{$auction->carListing->title}'.",
                        'extra'   => "Winning Bid: ₹{$data['auction']['current_bid']}. Our team will contact you for next steps."
                    ]
                );
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Auction result stored successfully'
        ]);
    }

    
}
