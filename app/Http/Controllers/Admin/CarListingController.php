<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarListing;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarFeature;
use App\Models\CarImage;
use App\Models\CarListingEditRequest;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Storage;

class CarListingController extends Controller
{
    public function index(Request $request)
{
    $query = CarListing::with(['make', 'model', 'user', 'booking', 'pendingEditRequest'])
        ->where('user_id', '!=', 1)
        ->where(function ($q) {
            $q->whereNull('sale_status')
              ->orWhere('sale_status', '!=', 'sold');
        });

    // 🔍 Search
    if ($request->filled('search')) {
        $query->where('title', 'LIKE', '%' . trim($request->search) . '%');
    }


    // 🚗 MAKE (CRITICAL FIX)
    if ($request->filled('make_id')) {
        $makeId = (int) $request->make_id;
        $make = CarMake::find($makeId);

        if ($make) {
            $query->where(function ($q) use ($makeId, $make) {
                $q->where('make_id', $makeId)
                  ->orWhere('make', $make->name); // legacy records
            });
        }
    }

    // 🚘 MODEL (same logic)
    if ($request->filled('model_id')) {
        $modelId = (int) $request->model_id;
        $model = CarModel::find($modelId);

        if ($model) {
            $query->where(function ($q) use ($modelId, $model) {
                $q->where('model_id', $modelId)
                  ->orWhere('model', $model->name);
            });
        }
    }

    // 📅 Year range
    if ($request->filled('year_from')) {
        $query->where('year', '>=', (int) $request->year_from);
    }

    if ($request->filled('year_to')) {
        $query->where('year', '<=', (int) $request->year_to);
    }

    // ⛽ Fuel
    if ($request->filled('fuel_type')) {
        $query->where('fuel_type', $request->fuel_type);
    }

    // ⚠️ Status (ENUM → STRING ONLY)
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 💰 Price range
    if ($request->filled('price_min')) {
        $query->where('price', '>=', (float) $request->price_min);
    }

    if ($request->filled('price_max')) {
        $query->where('price', '<=', (float) $request->price_max);
    }

    // 📦 Final result
    $carListings = $query
        ->orderBy('created_at', 'desc')
        ->paginate(30)
        ->withQueryString();

    // Filters data
    $makes = CarMake::orderBy('name')->get();
    $models = $request->make_id
        ? CarModel::where('make_id', $request->make_id)->orderBy('name')->get()
        : collect();

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

        // ✅ Send approval email to seller
        $user = $car->user;

        NotificationService::sendEmail(
            $user->email,
            'Car Listing Approved',
            [
                'name' => $user->name,
                'message' => "Great news! Your car '{$car->title}' has been approved and is now live on Morya Auto Hub."
            ]
        );

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

        // ✅ Send rejection email to seller
        $user = $car->user;

        NotificationService::sendEmail(
            $user->email,
            'Car Listing Rejected',
            [
                'name' => $user->name,
                'message' => "Unfortunately, your car '{$car->title}' has been rejected.",
                'extra' => "Reason: {$request->reason}"
            ]
        );
        
        return redirect()->back()->with('success', 'Car listing rejected successfully.');
    }

    public function editRequests()
    {
        $requests = CarListingEditRequest::with([
            'carListing.make',
            'carListing.model',
            'user'
        ])
        ->where('status', 'pending')
        ->latest()
        ->paginate(30);
        dd(0);
        return view('car_listings.edit_requests', compact('requests'));
    }
    public function approveEditRequest($id)
    {
        $edit = CarListingEditRequest::findOrFail($id);
        $listing = CarListing::findOrFail($edit->car_listing_id);

        foreach ($edit->changes as $field => $values) {
            $listing->$field = $values['new'];
        }

        $listing->status = 'approved';
        $listing->approved_at = now();
        $listing->save();

        $edit->update(['status' => 'approved']);

        return back()->with('success', 'Edit request approved');
    }

    public function rejectEditRequest(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        CarListingEditRequest::where('id', $id)->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);

        return back()->with('success', 'Edit request rejected');
    }

    public function sold(Request $request)
    {
        $query = CarListing::with(['make', 'model', 'user', 'booking'])
            ->where('sale_status', 'sold');

        // 🔍 Search (title / make / model)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                ->orWhere('make', 'LIKE', "%{$search}%")
                ->orWhere('model', 'LIKE', "%{$search}%");
            });
        }

        // 🚗 Make
        if ($request->filled('make_id')) {
            $make = CarMake::find($request->make_id);
            if ($make) {
                $query->where(function ($q) use ($make) {
                    $q->where('make_id', $make->id)
                    ->orWhere('make', $make->name);
                });
            }
        }

        // 🚘 Model
        if ($request->filled('model_id')) {
            $model = CarModel::find($request->model_id);
            if ($model) {
                $query->where(function ($q) use ($model) {
                    $q->where('model_id', $model->id)
                    ->orWhere('model', $model->name);
                });
            }
        }

        // 📅 Year range
        if ($request->filled('year_from')) {
            $query->where('year', '>=', (int) $request->year_from);
        }

        if ($request->filled('year_to')) {
            $query->where('year', '<=', (int) $request->year_to);
        }

        // 💰 Price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->price_max);
        }

        // 🧾 Sold date filter
        if ($request->filled('sold_from')) {
            $query->whereDate('sold_at', '>=', $request->sold_from);
        }

        if ($request->filled('sold_to')) {
            $query->whereDate('sold_at', '<=', $request->sold_to);
        }

        $soldCars = $query
            ->orderBy('sold_at', 'desc')
            ->paginate(30)
            ->withQueryString();

        $makes  = CarMake::orderBy('name')->get();
        $models = $request->make_id
            ? CarModel::where('make_id', $request->make_id)->orderBy('name')->get()
            : collect();

        return view('car_listings.sold', compact('soldCars', 'makes', 'models'));
    }

    public function moryaCarsIndex(Request $request)
    {
        $query = CarListing::where('user_id', 1);

        // 🔍 Make
        if ($request->filled('make_id')) {
            $query->where('make_id', $request->make_id);
        }

        // 🔍 Model
        if ($request->filled('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        // 🔍 Year range
        if ($request->filled('year_from')) {
            $query->where('year', '>=', $request->year_from);
        }

        if ($request->filled('year_to')) {
            $query->where('year', '<=', $request->year_to);
        }

        // 🔍 Fuel
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        // 🔍 Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔍 Keyword search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('make', 'LIKE', "%$search%")
                ->whereOr('model', 'LIKE', "%$search%")
                ->whereOr('registration_number', 'LIKE', "%$search%");
            });
        }

        $cars = $query->latest()->paginate(20)->withQueryString();

        // dropdown data
        $makes = CarMake::orderBy('name')->get();
        $models = CarModel::orderBy('name')->get();

        return view('car_listings.morya-index',compact('cars', 'makes', 'models'));
    }

    public function editSynced($id)
    {
        $car = CarListing::where('id', $id)
            ->where('user_id', 1)
            ->firstOrFail();

        return view('car_listings.edit-synced', compact('car'));
    }

    public function updateSynced(Request $request, $id)
    {
        $car = CarListing::where('id', $id)
            ->where('user_id', 1)
            ->firstOrFail();

        $request->validate([
            'price' => 'required|numeric',
            'expected_price' => 'nullable|numeric',

            'transmission' => 'required|in:Manual,Automatic',
            'body_type' => 'required|in:Hatchback,SUV,MUV,Coupe,Convertible,Pickup,Luxury',
            'accident' => 'required|in:no,minor,major',

            'location_city' => 'required|string|max:255',
            'registration_state' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

            'insurance_company' => 'nullable|string|max:100',
            'insurance_policy_number' => 'nullable|string|max:100',
            'insurance_upto' => 'nullable|date',

            'pucc_number' => 'nullable|string|max:50',
            'pucc_upto' => 'nullable|date',
        ]);

        // Update main listing
        $car->update([
            'price' => $request->price,
            'expected_price' => $request->expected_price,
            'is_negotiable' => $request->has('is_negotiable'),

            'transmission' => $request->transmission,
            'body_type' => $request->body_type,
            'accident' => $request->accident,

            'location_city' => $request->location_city,
            'registration_state' => $request->registration_state,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,

            'insurance_company' => $request->insurance_company,
            'insurance_policy_number' => $request->insurance_policy_number,
            'insurance_upto' => $request->insurance_upto,

            'pucc_number' => $request->pucc_number,
            'pucc_upto' => $request->pucc_upto,
        ]);

        /** 🔹 FEATURES */
        CarFeature::where('car_listing_id', $car->id)->delete();

        if ($request->features) {
            foreach ($request->features as $feature => $value) {
                CarFeature::create([
                    'car_listing_id' => $car->id,
                    'feature_name' => $feature,
                    'is_available' => $value,
                ]);
            }
        }

        return redirect()
            ->route('admin.morya-cars.index')
            ->with('success', 'Morya car updated successfully');
    }

    public function images($id)
    {
        $car = CarListing::with('images')
            ->where('id', $id)
            ->where('user_id', 1) // synced only
            ->firstOrFail();

        return view('car_listings.images', compact('car'));
    }

    public function storeImages(Request $request, $id)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $car = CarListing::where('id', $id)
            ->where('user_id', 1)
            ->firstOrFail();

        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('car_images', 'public');

            CarImage::create([
                'car_listing_id' => $car->id,
                'image_path' => $path,
                'is_primary' => $car->images()->count() === 0 && $index === 0 ? 1 : 0,
                'sort_order' => $index,
            ]);
        }

        return back()->with('success', 'Images uploaded successfully');
    }

    public function deleteImage($id)
    {
        $image = CarImage::findOrFail($id);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Image deleted');
    }

    public function setPrimaryImage($id)
    {
        $image = CarImage::findOrFail($id);

        CarImage::where('car_listing_id', $image->car_listing_id)
            ->update(['is_primary' => 0]);

        $image->update(['is_primary' => 1]);

        return back()->with('success', 'Primary image updated');
    }
}
