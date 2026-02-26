<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offer;

class OfferController extends Controller
{
     public function allActive()
    {
        $offers = Offer::where('is_active', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->select(
                'id',
                'title',
                'description',
                'discount_type',
                'discount_value',
                'applies_to',
                'start_date',
                'end_date'
            )
            ->get();

        return response()->json([
            'status' => true,
            'data' => $offers
        ]);
    }

    /**
     * Get offer by module (accessories / workshop / car_listing)
     */
    public function byModule($module)
    {
        if (!in_array($module, ['accessories', 'workshop', 'car_listing'])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid module'
            ], 400);
        }

        $offer = Offer::where('applies_to', $module)
            ->where('is_active', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->select(
                'id',
                'title',
                'description',
                'discount_type',
                'discount_value',
                'start_date',
                'end_date'
            )
            ->first();

        return response()->json([
            'status' => true,
            'data' => $offer
        ]);
    }
}
