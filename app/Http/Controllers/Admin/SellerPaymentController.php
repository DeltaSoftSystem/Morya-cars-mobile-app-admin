<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\SellerPayment;
use App\Services\NotificationService;


class SellerPaymentController extends Controller
{
    public function store(Request $request, Booking $booking)
{
    if ($booking->payment_status !== 'paid') {
        return back()->with('error', 'Complete buyer payment first.');
    }

    $sellerPayable = $booking->booking_amount;

    $totalPaid = $booking->sellerPayments()->sum('amount');

    if (($totalPaid + $request->amount) > $sellerPayable) {
        return back()->with('error', 'Payment exceeds seller payable amount.');
    }

    $path = $request->file('proof_file')->store('seller_payments', 'public');

    $booking->sellerPayments()->create([
        'seller_id' => $booking->carListing->user_id,
        'amount' => $request->amount,
        'payment_mode' => $request->payment_mode,
        'transaction_ref' => $request->transaction_ref,
        'payment_date' => $request->payment_date,
        'proof_file' => $path,
        'status' => 'paid',
        'admin_comment' => $request->admin_comment,
    ]);

    if (($totalPaid + $request->amount) == $sellerPayable) {
        $booking->update(['booking_status' => 'seller_paid']);
    }

    // Calculate again after insert
    $totalPaidAfter = $totalPaid + $request->amount;

    $seller = $booking->carListing->user;
    $buyer  = $booking->user;

    // 🟡 PARTIAL SELLER PAYMENT
    if ($totalPaidAfter < $sellerPayable) {

        NotificationService::sendEmail(
            $seller->email,
            'Partial Seller Payment Received',
            [
                'name'    => $seller->name,
                'message' => "A partial payment has been released for booking #{$booking->id}.",
                'extra'   => "Amount paid: ₹{$request->amount}. Remaining will be released soon."
            ]
        );
    }

    // 🟢 FULL SELLER PAYMENT
    if ($totalPaidAfter == $sellerPayable) {

        // Seller email
        NotificationService::sendEmail(
            $seller->email,
            'Seller Payment Completed',
            [
                'name'    => $seller->name,
                'message' => "Full payment for booking #{$booking->id} has been released to you.",
                'extra'   => "Total amount: ₹{$sellerPayable}."
            ]
        );
    }
    
    return back()->with('success', 'Seller payment recorded.');
}

}
