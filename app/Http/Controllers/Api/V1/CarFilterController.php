<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarListing;
use App\Models\CarMake;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarFilterController extends Controller
{
  
    // ------------ FILTER OPTIONS API -------------------
    public function getFilters()
    {
        $startYear  = 2010;
        $endYear    = Carbon::now()->year; // auto: 2026, 2027, etc
        $step       = 5;

        $yearRanges = [];

        for ($year = $startYear; $year <= $endYear; $year += $step) {
            $rangeStart = $year;
            $rangeEnd   = min($year + $step - 1, $endYear);

            $yearRanges[] = "{$rangeStart}-{$rangeEnd}";
        }

        return response()->json([
            'brands'        => CarListing::select('make')->distinct()->orderBy('make')->pluck('make'),
            'models'        => CarListing::select('model')->distinct()->orderBy('model')->pluck('model'),

            // ✅ Segment filter
            'segments'      => CarMake::select('segment')->distinct()->orderBy('segment')->pluck('segment'),
            // ✅ Dynamic year ranges
            'year_ranges'   => $yearRanges,

            'km_options'    => ["0-25000", "25000-50000", "50000-75000", "75000-100000", "100000+"],
            'fuel_types'    => CarListing::select('fuel_type')->distinct()->pluck('fuel_type'),
            'transmissions' => CarListing::select('transmission')->distinct()->pluck('transmission'),
            'body_types'    => CarListing::select('body_type')->distinct()->pluck('body_type'),
            'owner_counts'  => CarListing::select('owner_count')->distinct()->pluck('owner_count'),
            'cities'        => CarListing::select('location_city')->distinct()->pluck('location_city'),
        ]);
    }


    // ------------ SORT OPTIONS API -------------------
    public function getSortOptions()
    {
        return response()->json([
            ['key' => 'low_to_high', 'label' => 'Low → High'],
            ['key' => 'high_to_low', 'label' => 'High → Low']
        ]);
    }

    // ------------ MAIN CARS API WITH FILTERS + SORTING -------------------
    public function getCarListings(Request $request)
    {
        $query = CarListing::with('images')
            ->where('status', 'approved')
            ->where('sale_status','available'); // ✅ REQUIRED


           
     /* ---------------- SEGMENT FILTER (CORRECT) ---------------- */
    if ($request->filled('segment')) {
        // single segment
        $query->whereHas('make', function ($q) use ($request) {
            $q->where('segment', $request->segment);
        });
    }

    if ($request->filled('segments')) {
        // multiple segments
        $segments = is_array($request->segments)
            ? $request->segments
            : explode(',', $request->segments);

        $query->whereHas('make', function ($q) use ($segments) {
            $q->whereIn('segment', $segments);
        });
    }
    
        // ----- Filters -----
        if ($request->filled('make')) {
            $query->where('make', $request->make);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('year_range')) {
            $range = explode('-', $request->year_range);

            $minYear = (int) ($range[0] ?? 0);
            $maxYear = isset($range[1]) && is_numeric($range[1])
                ? (int) $range[1]
                : $minYear;

            $query->whereBetween('year', [$minYear, $maxYear]);
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->filled('body_type')) {
            $query->where('body_type', $request->body_type);
        }

        if ($request->filled('owner_count')) {
            $query->where('owner_count', $request->owner_count);
        }

        if ($request->filled('km')) {
            $range = explode('-', $request->km);
            $min = (int) ($range[0] ?? 0);
            $max = isset($range[1]) ? (int) $range[1] : null;

            if ($max) {
                $query->whereBetween('km_driven', [$min, $max]);
            } else {
                $query->where('km_driven', '>=', $min);
            }
        }

        if ($request->filled('city')) {
            $query->where('location_city', $request->city);
        }

        // ----- Sorting -----
        if ($request->sort === 'low_to_high') {
            $query->orderBy('price', 'asc');
        }

        if ($request->sort === 'high_to_low') {
            $query->orderBy('price', 'desc');
        }

        return response()->json([
            'status' => true,
            'cars' => $query->paginate(20)
        ]);
    }

}
