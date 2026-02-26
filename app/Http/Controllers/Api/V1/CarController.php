<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\CarListing;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarFeature;
use App\Models\CarImage;
use App\Models\CarListingEditRequest;
use App\Services\NotificationService;


class CarController extends Controller
{
     public function getMakes()
    {
        return response()->json([
            'status' => 'success',
            'data' => CarMake::orderBy('name')->get()
        ]);
    }

    /**
     * ✅ List models for a make
     */
    public function getModels(Request $request)
    {
        $query = CarModel::query();

        if ($request->make_id) {
            $query->where('make_id', $request->make_id);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->orderBy('name')->get()
        ]);
    }


    public function indexListings()
{
    $cars = CarListing::with(['images' => function ($q) {
        $q->where('is_primary', 1);
    }])
    ->where('status', 'approved') // 🔍 Filter only approved listings
    ->where('sale_status','available')
    ->orderBy('id', 'DESC')
    ->paginate(20);

    // Add full thumb URL
    $cars->getCollection()->transform(function ($car) {
        $car->primary_image = isset($car->images[0])
            ? URL::to('/storage/' . $car->images[0]->image_path)
            : null;
        unset($car->images);
        return $car;
    });

    
    return response()->json([
        'status' => 'success',
        'data' => $cars
    ]);
}

    function userwiesListing($userid)
    {
        $cars = CarListing::with(['images', 'features'])
            ->where('user_id', $userid)
            ->orderBy('id', 'DESC')
            ->get();

        // Convert image path to full URL
        $cars->each(function ($car) {
            $car->images->transform(function ($img) {
                $img->image_url = url('/storage/' . $img->image_path);
                return $img;
            });
        });

        return response()->json([
            'status' => 'success',
            'data' => $cars
        ]);
    }

    public function showListing($id)
    {
        $car = CarListing::with(['images', 'features'])->find($id);

        if (!$car) {
            return response()->json(['status' => 'error', 'message' => 'Car not found'], 404);
        }

        // Make image URLs absolute
        $car->images->transform(function ($img) {
            $img->image_url = URL::to('/storage/' . $img->image_path);
            return $img;
        });

        return response()->json([
            'status' => 'success',
            'data' => $car
        ]);
    }

     public function storeListing(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'price' => 'required|numeric',
            'killometer' => 'nullable|integer',
            'primary_index' => 'nullable|integer',
            'location_city' => 'nullable|string|max:255',
            'location_state' => 'nullable|string|max:255',
            'insurance_company' => 'nullable|string|max:100',
            'insurance_policy_number' => 'nullable|string|max:100',
            'insurance_upto' => 'nullable|date',

            'pucc_number' => 'nullable|string|max:50',
            'pucc_upto' => 'nullable|date',
        ]);

        // Create listing
        $car = CarListing::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'make_id'=>$request->make_id,
            'model_id'=>$request->model_id,
            'make' => $request->make,
            'model' => $request->model,
            'variant' => $request->variant,
            'year' => $request->year,
            'km_driven' => $request->km_driven,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'body_type' => $request->body_type,
            'color' => $request->color,
            'price' => $request->price,
            'is_negotiable' => $request->is_negotiable ?? 0,
            'owner_count' => $request->owner_count,
            'registration_state' => $request->registration_state,
            'registration_city' => $request->registration_city,
            'registration_number' => $request->registration_number,
            'insurance_company' => $request->insurance_company,
            // 🔹 Insurance & PUC
            'insurance_company' => $request->insurance_company,
            'insurance_policy_number' => $request->insurance_policy_number,
            'insurance_upto' => $request->insurance_upto,
            'pucc_number' => $request->pucc_number,
            'pucc_upto' => $request->pucc_upto,
            // location saved here
            'location_city' => $request->location_city,
            'location_state' => $request->location_state,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accident'=>$request->accident,
            'status' => 'pending', 
        ]);

        // /** ✅ Save images */
        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $index => $file) {

        //         $path = $file->store('car_images', 'public');

        //         CarImage::create([
        //             'car_listing_id' => $car->id,
        //             'image_path' => $path,
        //             'is_primary' => ($index == $request->primary_index) ? 1 : 0,
        //             'sort_order' => $index,
        //         ]);
        //     }
        // }

        /** ✅ Save features */
        if ($request->features) {
            foreach ($request->features as $featureName => $value) {
                CarFeature::create([
                    'car_listing_id' => $car->id,
                    'feature_name' => $featureName,
                    'is_available' => $value,
                ]);
            }
        }

        //send notification on mail
        $user = auth()->user();

        NotificationService::sendEmail(
            $user->email,
            'Car Listed Successfully',
            [
                'name' => $user->name,
                'message' => "Your car '{$car->title}' has been listed successfully and is currently under review."
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Car listing created successfully',
            'data' => $car
        ]);
    }

    

//car Listing Update
    public function update(Request $request, $id)
{
    $listing = CarListing::find($id);

    if (!$listing) {
        return response()->json([
            'status' => false,
            'message' => 'Car listing not found'
        ], 404);
    }

    $validated = $request->validate([
        'title' => 'nullable|string|max:255',
        'make' => 'nullable|string|max:255',
        'model' => 'nullable|string|max:255',
        'variant' => 'nullable|string|max:255',
        'year' => 'nullable|integer',
        'km_driven' => 'nullable|integer',
        'fuel_type' => 'nullable|string|max:50',
        'transmission' => 'nullable|string|max:50',
        'body_type' => 'nullable|string|max:50',
        'color' => 'nullable|string|max:50',
        'price' => 'nullable|numeric',
        'expected_price' => 'nullable|numeric',
        'is_negotiable' => 'nullable|boolean',
        'owner_count' => 'nullable|integer',
        'registration_state' => 'nullable|string|max:255',
        'registration_city' => 'nullable|string|max:255',
        'registration_number' => 'nullable|string|max:255',
        'insurance_company' => 'nullable|string|max:100',
        'insurance_policy_number' => 'nullable|string|max:100',
        'insurance_upto' => 'nullable|date',

        'pucc_number' => 'nullable|string|max:50',
        'pucc_upto' => 'nullable|date',
        'status' => 'nullable|string|in:pending,approved,rejected,inactive',
        'admin_rejection_reason' => 'nullable|string|max:255',
        'auction_status' => 'nullable|string|in:none,requested,approved,rejected,running,completed',
        'accident' => 'nullable|string|in:no,minor,major',
        
        // inspection
        'inspection_report_url' => 'nullable|string',
        'inspection_summary' => 'nullable|string',

        // boolean fields
        'is_featured' => 'nullable|boolean',
        'has_sunroof' => 'nullable|boolean',
        'has_navigation' => 'nullable|boolean',
        'has_parking_sensor' => 'nullable|boolean',
        'has_reverse_camera' => 'nullable|boolean',
        'has_airbags' => 'nullable|boolean',
        'has_abs' => 'nullable|boolean',
        'has_esp' => 'nullable|boolean',

        // location
        'location_city' => 'nullable|string|max:255',
        'location_state' => 'nullable|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        
    ]);

     /**
     * 🚦 DECIDE: creation flow or edit flow
     */
    $shouldCreateEditRequest = in_array(
        $listing->status,
        ['approved', 'rejected']
    );

    /**
     * 🟢 CASE 1: MULTI-STEP CREATION / FIRST SUBMISSION
     * status = pending | inactive
     */
    if (!$shouldCreateEditRequest) {

        $listing->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Car listing saved successfully',
            'data' => $listing
        ]);
    }

    /**
     * 🟡 CASE 2: REAL EDIT (AFTER ADMIN ACTION)
     */
    $changes = [];

    foreach ($validated as $field => $newValue) {
        if ($listing->$field != $newValue) {
            $changes[$field] = [
                'old' => $listing->$field,
                'new' => $newValue
            ];
        }
    }

    if (empty($changes)) {
        return response()->json([
            'status' => false,
            'message' => 'No changes detected'
        ]);
    }

    // allow only one pending edit request
    CarListingEditRequest::where('car_listing_id', $listing->id)
        ->where('status', 'pending')
        ->delete();

    CarListingEditRequest::create([
        'car_listing_id' => $listing->id,
        'user_id' => auth()->id(),
        'changes' => $changes,
        'status' => 'pending'
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Changes sent for admin approval'
    ]);
}


    /**
     * ✅ Store a single image (extra)
     */
    public function storeImage(Request $request, $listing_id)
{
    $request->validate([
        'images' => 'required',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
    ]);

    $uploadedImages = [];

    // Check if primary image exists
    $alreadyHasPrimary = CarImage::where('car_listing_id', $listing_id)
        ->where('is_primary', 1)
        ->exists();

    foreach ($request->file('images') as $file) {

        $isPrimary = $alreadyHasPrimary ? 0 : 1;
        $alreadyHasPrimary = true; // after first insert

        $path = $file->store('car_images', 'public');

        $image = CarImage::create([
            'car_listing_id' => $listing_id,
            'image_path'     => $path,
            'is_primary'     => $isPrimary,
            'sort_order'     => 0,
        ]);

        $uploadedImages[] = $image;
    }

    return response()->json([
        'status' => 'success',
        'data'   => $uploadedImages
    ]);
}

public function setPrimary($listing_id, $image_id)
{
    // Check if the image exists and belongs to the listing
    $image = CarImage::where('id', $image_id)
        ->where('car_listing_id', $listing_id)
        ->first();

    if (!$image) {
        return response()->json([
            'status' => false,
            'message' => 'Image not found for this listing'
        ], 404);
    }

    // Reset previous primary images for this listing
    CarImage::where('car_listing_id', $listing_id)
        ->update(['is_primary' => 0]);

    // Set the selected image as primary
    $image->update(['is_primary' => 1]);

    // Fetch all images of the listing, primary first
    $images = CarImage::where('car_listing_id', $listing_id)
        ->orderBy('is_primary', 'desc')
        ->orderBy('created_at', 'asc') // optional: maintain upload order
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Primary image updated successfully',
        'data' => $images
    ]);
}


public function getImages($car_listing_id)
{
    // Validate car_listing_id exists (optional)
    $listingExists = CarListing::find($car_listing_id);
    if (!$listingExists) {
        return response()->json([
            'status' => false,
            'message' => 'Car listing not found'
        ], 404);
    }

    // Fetch images
    $images = CarImage::where('car_listing_id', $car_listing_id)->orderBy('is_primary', 'desc')->get();

    return response()->json([
        'status' => true,
        'data' => $images
    ]);
}


    /**
     * ✅ Delete a car image
     */
    public function deleteImage($image_id)
    {
        $image = CarImage::find($image_id);

        if (!$image) {
            return response()->json(['status' => 'error', 'message' => 'Image not found'], 404);
        }

        $image->delete();

        return response()->json(['status' => 'success', 'message' => 'Image deleted']);
    }

    /**
     ✅ Get features for a listing
     */
    public function getFeatures($listing_id)
    {
        return response()->json([
            'status' => 'success',
            'data' => CarFeature::where('car_listing_id', $listing_id)->get()
        ]);
    }


    // public function index(Request $request)
    // {
    //     $query = CarListing::with(['images' => function ($q) {
    //         $q->where('is_primary', 1);
    //     }]);

    //     // Optional Filters
    //     if ($request->make) {
    //         $query->where('make', $request->make);
    //     }
    //     if ($request->model) {
    //         $query->where('model', $request->model);
    //     }
    //     if ($request->year) {
    //         $query->where('year', $request->year);
    //     }
    //     if ($request->fuel_type) {
    //         $query->where('fuel_type', $request->fuel_type);
    //     }
    //     if ($request->min_price) {
    //         $query->where('price', '>=', $request->min_price);
    //     }
    //     if ($request->max_price) {
    //         $query->where('price', '<=', $request->max_price);
    //     }

    //     $cars = $query->orderBy('id', 'DESC')->paginate(20);

    //     // Format primary image URL
    //     $cars->getCollection()->transform(function ($car) {
    //         $car->primary_image = $car->images->first()
    //             ? URL::to('/storage/car_listing/' . $car->images->first()->image_path)
    //             : null;
    //         unset($car->images);
    //         return $car;
    //     });

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $cars
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'make' => 'required|string',
    //         'model' => 'required|string',
    //         'variant' => 'nullable|string',
    //         'year' => 'required|integer',
    //         'km_driven' => 'required|integer',
    //         'fuel_type' => 'required|string',
    //         'transmission' => 'required|string',
    //         'body_type' => 'nullable|string',
    //         'color' => 'nullable|string',
    //         'price' => 'required|numeric',
    //         'is_negotiable' => 'nullable|boolean',
    //         'owner_count' => 'nullable|integer',
    //         'registration_state' => 'nullable|string',
    //         'registration_city' => 'nullable|string',
    //         'registration_number' => 'nullable|string',

    //         // Features
    //         'has_sunroof' => 'nullable|boolean',
    //         'has_navigation' => 'nullable|boolean',
    //         'has_parking_sensor' => 'nullable|boolean',
    //         'has_reverse_camera' => 'nullable|boolean',
    //         'has_airbags' => 'nullable|boolean',
    //         'has_abs' => 'nullable|boolean',
    //         'has_esp' => 'nullable|boolean',

    //         // Images
    //         'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //         'primary_index' => 'nullable|integer'
    //     ]);

    //     // Create listing
    //     $car = CarListing::create([
    //         'user_id' => auth()->id(),
    //         'title' => $request->title,
    //         'make' => $request->make,
    //         'model' => $request->model,
    //         'variant' => $request->variant,
    //         'year' => $request->year,
    //         'km_driven' => $request->km_driven,
    //         'fuel_type' => $request->fuel_type,
    //         'transmission' => $request->transmission,
    //         'body_type' => $request->body_type,
    //         'color' => $request->color,
    //         'price' => $request->price,
    //         'is_negotiable' => $request->is_negotiable ?? 0,
    //         'owner_count' => $request->owner_count,
    //         'registration_state' => $request->registration_state,
    //         'registration_city' => $request->registration_city,
    //         'registration_number' => $request->registration_number,
    //         'status' => 'pending',
    //         'is_featured' => 0,
    //         'views_count' => 0,
    //         'leads_count' => 0,

    //         // Features
    //         'has_sunroof' => $request->has_sunroof ?? 0,
    //         'has_navigation' => $request->has_navigation ?? 0,
    //         'has_parking_sensor' => $request->has_parking_sensor ?? 0,
    //         'has_reverse_camera' => $request->has_reverse_camera ?? 0,
    //         'has_airbags' => $request->has_airbags ?? 0,
    //         'has_abs' => $request->has_abs ?? 0,
    //         'has_esp' => $request->has_esp ?? 0,
    //     ]);

    //     // ✅ Store images
    //     if ($request->hasFile('images')) {
    //         foreach ($request->file('images') as $index => $imageFile) {

    //             $path = $imageFile->store('car_listing', 'public');

    //             $car->images()->create([
    //                 'image_path' => $path,
    //                 'is_primary' => ($index == $request->primary_index) ? 1 : 0,
    //                 'sort_order' => $index
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Car listing created successfully',
    //         'data' => $car->load('images')
    //     ], 201);
    // }
    /**
     * ✅ Single Car Detail API (with full gallery)
     */
    // public function show($id)
    // {
    //     $car = CarListing::with('images')->find($id);

    //     if (!$car) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Car not found'
    //         ], 404);
    //     }

    //     // Attach image URLs
    //     $car->images->transform(function ($img) {
    //         $img->image_url = URL::to('/storage/car_listing/' . $img->image_path);
    //         return $img;
    //     });

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $car
    //     ]);
    // }
}
