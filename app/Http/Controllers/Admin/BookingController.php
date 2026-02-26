<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\PaymentProof;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;


class BookingController extends Controller
{
   // List all bookings
    public function index()
    {
        $bookings = Booking::with('user', 'carListing')->latest()->paginate(20);
        
        return view('bookings.index', compact('bookings'));
    }

    // View a booking
    public function show($id)
    {
        $booking = Booking::with('user', 'carListing', 'paymentProofs')->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    // Update booking (status, admin comment)
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'payment_status' => $request->payment_status,
            'booking_status' => $request->booking_status,
            'admin_comment' => $request->admin_comment,
        ]);

        return back()->with('success', 'Booking updated successfully');
    }

    // Upload payment proof
 public function uploadProof(Request $request, $booking_id)
{
    $request->validate([
        'file' => 'required|mimes:jpeg,png,jpg,pdf|max:4096',
        'utr_number' => 'nullable|string|max:100',
        'amount' => 'required|numeric|min:1',
        'payment_date' => 'nullable|date',
    ]);

    $booking = Booking::findOrFail($booking_id);

    if ($booking->booking_status === 'cancelled') {
        return back()->with('error', 'Cannot upload proof for cancelled booking.');
    }

    // ✅ Total already uploaded (pending + verified)
    $alreadyUploaded = $booking->paymentProofs()
        ->whereIn('status', ['pending', 'verified'])
        ->sum('amount');

    // ❌ Prevent overpayment
    if (($alreadyUploaded + $request->amount) > $booking->booking_amount) {
        return back()->with(
            'error',
            'Payment amount exceeds booking amount.'
        );
    }

    $path = $request->file('file')->store('payment_proofs', 'public');

    $booking->paymentProofs()->create([
        'file_path'    => $path,
        'utr_number'   => $request->utr_number,
        'amount'       => $request->amount,
        'payment_date' => $request->payment_date,
        'status'       => 'pending',
    ]);

    $booking->update([
        'payment_status' => 'partial',
        'booking_status' => 'pending_payment',
    ]);

    return back()->with('success', 'Payment proof uploaded successfully.');
}




    // Delete payment proof
    public function deleteProof($proof_id)
    {
        $proof = PaymentProof::findOrFail($proof_id);
        Storage::disk('public')->delete($proof->file_path);
        $proof->delete();

        return back()->with('success', 'Payment proof deleted');
    }

 public function verifyPaymentProof($proof_id)
{
    DB::transaction(function () use ($proof_id) {

        $proof   = PaymentProof::with('booking.carListing')->findOrFail($proof_id);
        $booking = $proof->booking;
        $car     = $booking->carListing;

        if ($proof->status === 'verified') {
            throw new \Exception('Already verified.');
        }

        // ✅ Verify proof
        $proof->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // ✅ Calculate total verified payment
        $totalPaid = $booking->paymentProofs()
            ->where('status', 'verified')
            ->sum('amount');

        // 🟢 FULL PAYMENT
        if ($totalPaid >= $booking->booking_amount) {

            $booking->update([
                'payment_status' => 'paid',
                'booking_status' => 'confirmed',
                'paid_at'        => now(),
            ]);

            // 🔴 MARK CAR AS SOLD
            if ($car->sale_status !== 'sold') {
                $car->update([
                    'sale_status' => 'sold',
                    'sold_at'     => now(),
                ]);
            }

        } else {

            // 🟡 PARTIAL PAYMENT
            $booking->update([
                'payment_status' => 'partial',
            ]);

            // Optional: mark reserved
            if ($car->sale_status === 'available') {
                $car->update([
                    'sale_status' => 'reserved',
                ]);
            }
        }
    });
    $proof   = PaymentProof::with('booking.user', 'booking.carListing')->findOrFail($proof_id);
    $booking = $proof->booking;
    $buyer   = $booking->user;
    $car     = $booking->carListing;
    $seller  = $car->user;

    // 🟢 FULL PAYMENT CONFIRMED
    if ($booking->booking_status === 'confirmed') {

        // 📧 Buyer – Booking confirmed
        NotificationService::sendEmail(
            $buyer->email,
            'Booking Confirmed',
            [
                'name'    => $buyer->name,
                'message' => "Your booking for '{$car->title}' is confirmed. Payment received successfully."
            ]
        );

        // 📧 Seller – Car sold
        NotificationService::sendEmail(
            $seller->email,
            'Your Car Has Been Sold 🎉',
            [
                'name'    => $seller->name,
                'message' => "Congratulations! Your car '{$car->title}' has been sold successfully.",
                'extra'   => "Booking ID: {$booking->id}. Seller payment will be processed shortly."
            ]
        );

        // 📧 Buyer – Car purchased (confirmation)
        NotificationService::sendEmail(
            $buyer->email,
            'Car Purchase Successful',
            [
                'name'    => $buyer->name,
                'message' => "Your purchase of '{$car->title}' is successful.",
                'extra'   => 'The car is now marked as sold.'
            ]
        );

    } 
    else {

        // 🟡 PARTIAL PAYMENT
        NotificationService::sendEmail(
            $buyer->email,
            'Partial Payment Received',
            [
                'name'    => $buyer->name,
                'message' => "We have received a partial payment for '{$car->title}'. Please complete the remaining amount."
            ]
        );
    }

    return back()->with('success', 'Payment proof verified successfully.');
}

public function rejectPaymentProof($proof_id)
{
    $proof = PaymentProof::findOrFail($proof_id);

    $proof->update(['status' => 'rejected']);

    $user = $proof->booking->user;
    $car  = $proof->booking->carListing;

    // ❌ Send rejection email
    NotificationService::sendEmail(
        $user->email,
        'Payment Rejected',
        [
            'name'    => $user->name,
            'message' => "Your payment for '{$car->title}' was rejected.",
            'extra'   => 'Please upload a valid payment proof.'
        ]
    );
    // ❌ DO NOT cancel booking automatically
    return back()->with('error', 'Payment proof rejected.');
}

public function cancel(Request $request, Booking $booking)
{
    // 1️⃣ Block cancellation if seller already paid
    if ($booking->sellerPayment) {
        return back()->with('error', 'Cannot cancel booking after seller payment.');
    }

    // 2️⃣ Allow only valid statuses
    if (!in_array($booking->booking_status, ['pending_payment', 'confirmed'])) {
        return back()->with('error', 'Booking cannot be cancelled.');
    }

    // 3️⃣ Validate reason
    $request->validate([
        'admin_comment' => 'required|string|max:255',
    ]);

    // 4️⃣ Cancel booking
    $booking->update([
        'booking_status' => 'cancelled',
        'admin_comment'  => $request->admin_comment,
        'payment_status' => 'refunded', // optional / future-ready
    ]);

    $user = $booking->user;
    $car  = $booking->carListing;

    // ❌ Booking cancelled email
    NotificationService::sendEmail(
        $user->email,
        'Booking Cancelled',
        [
            'name'    => $user->name,
            'message' => "Your booking for '{$car->title}' has been cancelled.",
            'extra'   => "Reason: {$request->admin_comment}"
        ]
    );

    return back()->with('success', 'Booking cancelled successfully.');
}

}
