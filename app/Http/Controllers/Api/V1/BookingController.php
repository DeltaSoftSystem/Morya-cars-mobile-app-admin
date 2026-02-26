<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\PaymentProof;
use App\Services\NotificationService;

class BookingController extends Controller
{
     // 1. Create Booking (Book Now)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'car_listing_id' => 'required',
            'booking_amount' => 'required|numeric',
        ]);

        $booking = Booking::create([
            'user_id' => $request->user_id,
            'car_listing_id' => $request->car_listing_id,
            'booking_amount' => $request->booking_amount,
            'booking_status' => 'pending_payment',
        ]);

        // ✅ Send booking created email
        $user = $booking->user;
        $car  = $booking->carListing;

        NotificationService::sendEmail(
            $user->email,
            'Booking Created',
            [
                'name'    => $user->name,
                'message' => "Your booking for '{$car->title}' has been created. Please complete the payment to confirm."
            ]
        );
        
        return response()->json([
            'status' => 'success',
            'message' => 'Booking created. Please complete manual payment.',
            'data' => $booking
        ]);
    }

    // 2. Upload Manual Payment Proof
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,png,jpeg,pdf',
            'utr_number' => 'nullable|string',
            'payment_date' => 'nullable|string'
        ]);

        $booking = Booking::findOrFail($id);

        // Upload file
        $path = $request->file('file')->store('payment_proofs', 'public');

        $proof = PaymentProof::create([
            'booking_id' => $booking->id,
            'file_path' => $path,
            'utr_number' => $request->utr_number,
            'payment_date' => $request->payment_date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment proof uploaded. Pending verification.',
            'data' => $proof
        ]);
    }

    // 3. View Booking Status
    public function show($userId)
    {
        $bookings = Booking::with('paymentProofs')
        ->where('user_id', $userId)
        ->get();
        
      return response()->json([
          'status' => 'success',
          'data' => $bookings
      ]);
    }

}
