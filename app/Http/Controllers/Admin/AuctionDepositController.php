<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuctionDeposit;
use App\Services\AuctionFirebaseService;
use App\Services\NotificationService;


class AuctionDepositController extends Controller
{
    public function index()
    {
        $deposits = AuctionDeposit::with('user','auction')->latest()->paginate(20);
        return view('admin.auctions.deposits_list', compact('deposits'));
    }

    // Approve payment
    public function approve($id)
    {
        $deposit = AuctionDeposit::with(['auction', 'user'])->findOrFail($id);

        // 1️⃣ Update deposit status
        $deposit->update([
            'status' => 'approved',
            'admin_comment' => 'Approved for bidding access'
        ]);

        // 2️⃣ Push participant to Firebase 🔥
        app(AuctionFirebaseService::class)
            ->syncParticipant($deposit->auction, $deposit->user);

        // 📧 Deposit approved email
        NotificationService::sendEmail(
            $deposit->user->email,
            'Auction Deposit Approved',
            [
                'name'    => $deposit->user->name,
                'message' => 'Your auction deposit has been approved.',
                'extra'   => 'You can now participate and place bids in the auction.'
            ]
        );

        return back()->with(
            'success',
            'Deposit approved. User can now participate in auction.'
        );
    }

    // Reject payment
    public function reject(Request $request, $id)
    {
        $deposit = AuctionDeposit::with(['user'])->findOrFail($id);

        $deposit->update([
            'status' => 'rejected',
            'admin_comment' => $request->comment
        ]);


        // 📧 Deposit rejected email
        NotificationService::sendEmail(
            $deposit->user->email,
            'Auction Deposit Rejected',
            [
                'name'    => $deposit->user->name,
                'message' => 'Your auction deposit has been rejected.',
                'extra'   => "Reason: {$request->comment}"
            ]
        );

        return back()->with('success','Deposit rejected.');
    }

    // Refund deposit
    public function refund($id)
    {
        $deposit = AuctionDeposit::with(['user'])->findOrFail($id);

        $deposit->update([
            'status' => 'refunded',
            'admin_comment' => 'Amount refunded to user'
        ]);

        // 📧 Deposit refunded email
        NotificationService::sendEmail(
            $deposit->user->email,
            'Auction Deposit Refunded',
            [
                'name'    => $deposit->user->name,
                'message' => 'Your auction deposit has been refunded successfully.',
                'extra'   => 'The amount will be credited back as per the original payment method.'
            ]
        );

        return back()->with('success','Deposit refunded.');
    }
}
