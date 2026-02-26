<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accessory;
use App\Models\AccessoryBooking;

class AccessoryBookingController extends Controller
{
     public function store(Request $request)
    {
        $request->validate([
            'accessory_id' => 'required|exists:accessories,id',
            'quantity'     => 'required|integer|min:1',
            'name'         => 'required|string|max:150',
            'mobile'       => 'required|string|max:20',
            'email'        => 'nullable|email|max:150',
            'address'      => 'nullable|string'
        ]);

        $accessory = Accessory::where('status', 1)
            ->findOrFail($request->accessory_id);

        // Optional stock check (recommended)
        if ($accessory->stock < $request->quantity) {
            return response()->json([
                'status' => false,
                'message' => 'Requested quantity not available'
            ], 400);
        }

        $unitPrice = $accessory->discounted_price;
        $total = $unitPrice * $request->quantity;

        $booking = AccessoryBooking::create([
            'user_id'      => auth()->id(), // nullable
            'accessory_id' => $accessory->id,
            'quantity'     => $request->quantity,
            'unit_price'   => $unitPrice,
            'total_amount' => $total,
            'name'         => $request->name,
            'mobile'       => $request->mobile,
            'email'        => $request->email,
            'address'      => $request->address,
            'status'       => 'pending'
        ]);

        // OPTIONAL: do NOT reduce stock now (since payment is manual)

        return response()->json([
            'status' => true,
            'message' => 'Booking request submitted successfully',
            'data' => [
                'booking_id' => $booking->id,
                'quantity' => $booking->quantity,
                'total_amount' => $booking->total_amount
            ]
        ]);
    }
}
