<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceRequest;

class ServiceRequestController extends Controller
{
     public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'service_item_id' => 'nullable|exists:service_items,id',
            'car_id' => 'nullable|exists:car_listings,id',
            'name' => 'required|string|max:150',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:150',
            'city' => 'required|string|max:100',
            'pincode' => 'required|string|max:6',
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|string|max:50',
            'description' => 'nullable|string'
        ]);

        $serviceRequest = ServiceRequest::create([
            'user_id' => $request->user_id,
            'service_id' => $request->service_id,
            'service_item_id' => $request->service_item_id,
            'car_id' => $request->car_listing_id,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'city' => $request->city,
            
            'pincode' => $request->pincode,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service request submitted successfully',
            'data' => [
                'request_id' => $serviceRequest->id,
                'status' => $serviceRequest->status
            ]
        ], 201);
    }
}
