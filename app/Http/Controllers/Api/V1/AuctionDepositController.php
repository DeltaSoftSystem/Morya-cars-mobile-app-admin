<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuctionDeposit;
use App\Models\Auction;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;


class AuctionDepositController extends Controller
{
    public function store(Request $request, $auction)
    {
        $request->validate([
            'deposit_amount' => 'required|numeric|min:1'
        ]);

        // check if already exists
        $exists = AuctionDeposit::where('auction_id',$auction)
                  ->where('user_id',$request->user()->id)
                  ->whereIn('status',['pending','approved'])
                  ->first();
        if($exists){
            return response()->json(['status'=>false,'message'=>'Deposit already submitted.'],400);
        }

        $deposit = AuctionDeposit::create([
            'auction_id'     => $auction,
            'user_id'        => $request->user()->id,
            'deposit_amount' => $request->deposit_amount,
            'status'         => 'pending'
        ]);

        $user    = $request->user();
        $auctionModel = $deposit->auction;

        // 📧 Deposit request email
        NotificationService::sendEmail(
            $user->email,
            'Auction Deposit Submitted',
            [
                'name'    => $user->name,
                'message' => "Your deposit request for the auction has been submitted successfully.",
                'extra'   => "Deposit Amount: ₹{$deposit->deposit_amount}. Please upload payment proof to continue."
            ]
        );
        
        return response()->json([
            'status'=>true,
            'message'=>'Deposit request created. Upload proof to continue.',
            'data'=>$deposit
        ]);
    }


    // 2️⃣ Upload Payment Proof (File)
    public function uploadProof(Request $request, $auction)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $deposit = AuctionDeposit::where('auction_id',$auction)
                    ->where('user_id',$request->user()->id)
                    ->firstOrFail();

        // store file
        $path = $request->file('payment_proof')->store('auction_proofs','public');
        $deposit->payment_proof = $path;
        $deposit->status = 'pending';
        $deposit->save();

        return response()->json([
            'status'=>true,
            'message'=>'Payment proof uploaded successfully, waiting for admin approval.',
            'file_url'=>asset('storage/'.$path)
        ]);
    }


    // 3️⃣ Show Logged-In User Deposits
    public function myDeposits(Request $request)
    {
        $deposits = AuctionDeposit::with('auction')
            ->where('user_id',$request->user()->id)
            ->orderBy('id','desc')
            ->get();

        return response()->json([
            'status'=>true,
            'data'=>$deposits
        ]);
    }
}
