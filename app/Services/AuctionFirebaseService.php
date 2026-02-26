<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\CarListing;
use App\Models\AppUser;

class AuctionFirebaseService
{
    public function syncAuction(Auction $auction)
    {
        app(FirebaseService::class)->setDocument(
            'auctions',
            (string) $auction->id,
            [
                'id'            => $auction->id,
                'start_at'      => optional($auction->start_at)->toDateTimeString(),
                'end_at'        => optional($auction->end_at)->toDateTimeString(),
                'base_price'    => $auction->base_price,
                'bid_increment' => $auction->bid_increment,
                'status'        => 'scheduled',
                'car_listing'   => json_encode($auction->carListing),
                'updated_at'    => now()->toDateTimeString(),
            ]
        );
    }

    /**
     * 🔥 Sync auction participant to Firebase
     */
    public function syncParticipant(Auction $auction, AppUser $user)
    {
        app(FirebaseService::class)->setDocument(
            'auctions/' . $auction->id . '/participants',
            (string) $user->id,
            [
                'user_id'   => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'mobile'    => $user->mobile,
                'auction_id'=> $auction->id,
                'joined_at' => now()->toDateTimeString(),
            ]
        );
    }

    public function removeAuction(int $auctionId)
    {
        app(FirebaseService::class)
            ->deleteDocument('auctions', (string) $auctionId);
    }

    /**
     * Remove participant from Firebase
     */
    public function removeParticipant(int $auctionId, int $userId)
    {
        app(FirebaseService::class)->deleteDocument(
            'auctions/' . $auctionId . '/participants',
            (string) $userId
        );
    }
}
