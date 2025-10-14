<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarListing;
use App\Models\CarMake;
use App\Models\CarModel;

class CarListingController extends Controller
{
    public function index(Request $request)
    {
           $query = CarListing::with('make', 'model', 'user');

            // Keyword search (title)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('title', 'like', "%$search%");
            }

            // Filters
            if ($request->filled('make_id')) {
                $query->where('make_id', $request->make_id);
            }

            if ($request->filled('model_id')) {
                $query->where('model_id', $request->model_id);
            }

            if ($request->filled('year_from')) {
                $query->where('year', '>=', $request->year_from);
            }

            if ($request->filled('year_to')) {
                $query->where('year', '<=', $request->year_to);
            }

            if ($request->filled('fuel_type')) {
                $query->where('fuel_type', $request->fuel_type);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('price_min')) {
                $query->where('price', '>=', $request->price_min);
            }

            if ($request->filled('price_max')) {
                $query->where('price', '<=', $request->price_max);
            }

            $carListings = $query->orderBy('created_at', 'desc')->paginate(10);

            // Fetch all makes for filter
            $makes = CarMake::orderBy('name')->get();

            // Fetch models for the selected make if any
            $models = $request->filled('make_id') ? CarModel::where('make_id', $request->make_id)->orderBy('name')->get() : collect();
            return view('car_listings.index', compact('carListings', 'makes', 'models'));
    }

    public function getModels($makeId)
    {
        $models = CarModel::where('make_id', $makeId)->orderBy('name')->get();
        return response()->json($models);
    }

    // Show single car details
    public function show(CarListing $car)
    {
        $car->load('images', 'features', 'inspections', 'user');
        return view('car_listings.show', compact('car'));
    }

    // Approve a car listing
    public function approve(CarListing $car)
    {
        $car->status = 'approved';
        $car->approved_at = now();
        $car->admin_rejection_reason = null;
        $car->save();

        return redirect()->back()->with('success', 'Car listing approved successfully.');
    }

    // Reject a car listing
    public function reject(Request $request, CarListing $car)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $car->status = 'rejected';
        $car->admin_rejection_reason = $request->reason;
        $car->save();

        return redirect()->back()->with('success', 'Car listing rejected successfully.');
    }
}
