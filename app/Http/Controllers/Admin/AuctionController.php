<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarListing;
use App\Models\Auction;
use Illuminate\Support\Facades\Auth;
use App\Services\AuctionFirebaseService;
use App\Services\NotificationService;


class AuctionController extends Controller
{
    // 1) Requested Auctions (Pending)
    public function requested()
    {
        $carListings = CarListing::with('user')
                    ->where('status','approved')
                    ->whereIn('auction_status', ['requested','approved'])
                    ->paginate(10);

        return view('admin.auctions.requested', compact('carListings'));
    }

    // 2) Approved & Scheduled Auctions
    public function scheduled()
{
    $auctions = Auction::with('carListing')
        ->whereHas('carListing', fn($q) => $q->where('auction_status', 'scheduled'))
        ->where('end_at', '>', now())
        ->paginate(10); // this returns a collection (LengthAwarePaginator)

    return view('admin.auctions.scheduled', compact('auctions')); // pass $auctions plural
}


    // 3) Auction History + Bid List
    public function history(Request $request)
{
    $search = $request->search;

    $auctions = Auction::with([
            'carListing',
            'carListing.user',
            'bids.user',
            'resultWinner'   
        ])
        ->leftJoin('auction_results', 'auction_results.auction_id', '=', 'auctions.id')
        ->select(
            'auctions.*',
            'auction_results.current_bid',
            'auction_results.winner_name',
            'auction_results.winner_user_id',
            'auction_results.end_at as ended_on'
        )
        ->where('auctions.end_at', '<', now())

        // ✅ Proper grouped search
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('auctions.id', 'like', "%{$search}%")
                  ->orWhereHas('carListing', function ($q2) use ($search) {
                      $q2->where('title', 'like', "%{$search}%");
                  })
                  ->orWhereHas('carListing.user', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        })

        ->orderBy('auctions.end_at', 'DESC')
        ->paginate(10)
        ->appends(['search' => $search]);

    return view('admin.auctions.history', compact('auctions', 'search'));
}


    // Approve Auction
   public function approve(CarListing $carListing)
{
    $carListing->auction_status = 'approved';
    $carListing->save();

    $seller = $carListing->user;

    // 📧 Auction approved email
    NotificationService::sendEmail(
        $seller->email,
        'Auction Approved',
        [
            'name'    => $seller->name,
            'message' => "Your car '{$carListing->title}' has been approved for auction.",
            'extra'   => 'Our team will schedule the auction shortly.'
        ]
    );

    return redirect()->back()->with('success', 'Auction approved successfully.');
}

public function reject(CarListing $carListing)
{
    $carListing->auction_status = 'rejected';
    $carListing->save();
    // Remove auction if exists
    if ($carListing->auction) {
        app(AuctionFirebaseService::class)
            ->removeAuction($carListing->auction->id);
    }

    $seller = $carListing->user;

    // 📧 Auction rejected email
    NotificationService::sendEmail(
        $seller->email,
        'Auction Rejected',
        [
            'name'    => $seller->name,
            'message' => "Unfortunately, your car '{$carListing->title}' was rejected for auction."
        ]
    );
    
    return redirect()->back()->with('success', 'Auction rejected successfully.');
}

   // Show the schedule form
public function showScheduleForm(CarListing $auction) // $auction is actually a CarListing
{
    if ($auction->auction_status !== 'approved') {
        return redirect()->back()->with('error', 'Only approved auctions can be scheduled.');
    }

    return view('admin.auctions.schedule', compact('auction'));
}

// Handle form submission
public function schedule(Request $request, CarListing $auction)
{
    // Validate input
    $request->validate([
        'start_at' => 'required|date|after:now',
        'end_at'   => 'required|date|after:start_at',
        'base_price' => 'required|numeric|min:0',
        'bid_increment' => 'required|numeric|min:0',
    ]);

    // Create new auction record
    $newAuction = Auction::create([
        'car_listing_id' => $auction->id,
        'start_at' => $request->start_at,
        'end_at' => $request->end_at,
        'base_price' => $request->base_price,
        'bid_increment' => $request->bid_increment,
        'created_by'     => auth()->id(), 
    ]);

    // Update car listing status
    $auction->auction_status = 'scheduled';
    $auction->save();

    // 🔥 PUSH LIVE AUCTION TO FIREBASE
    app(AuctionFirebaseService::class)->syncAuction($newAuction);
    
    return redirect()->route('auctions.scheduled')
        ->with('success', 'Auction scheduled successfully.');
}



public function show($id)
{
    $auction = Auction::with([
        'carListing.user',   // ✅ seller
        'bids.user',         // bidders
        'result',            // auction_results
        'result.winnerUser'       // winner user
    ])->findOrFail($id);

    return view('admin.auctions.show', compact('auction'));
}

}
