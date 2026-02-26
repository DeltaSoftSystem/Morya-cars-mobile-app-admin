<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CarListing;
use App\Models\CarMake;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = CarListing::with('images')
            ->whereNull('deleted_at')
            ->whereIn('status', ['approved', 'active']) // adjust if needed
            ->orderByDesc('id')
            ->paginate(12);
        
        $carCount=CarListing::with('images')
            ->whereNull('deleted_at')
            ->whereIn('status', ['approved', 'active'])
            ->count();
        $brands = CarMake::select('name')->get();
        
        return view('web.buy.index', compact('cars','brands','carCount'));
    }

    public function cars(Request $request)
{
    $query = CarListing::query();

    if ($request->brand) {
        $query->where('make', $request->brand);
    }

    if ($request->body_type) {
        $query->where('body_type', $request->body_type);
    }

    if ($request->min_price) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->max_price) {
        $query->where('price', '<=', $request->max_price);
    }

    // Sorting
    if ($request->sort == 'price_low') {
        $query->orderBy('price', 'asc');
    } elseif ($request->sort == 'price_high') {
        $query->orderBy('price', 'desc');
    } elseif ($request->sort == 'year_new') {
        $query->orderBy('year', 'desc');
    } else {
        $query->latest();
    }

    // IMPORTANT: use paginate
    $cars = $query->paginate(9);

    return response()->json($cars);
}

    public function show(CarListing $car)
    {
        // Load images relation
        $car->load([
            'images',
            'features' => function($query) {
                $query->where('is_available', 1);
            }
        ]);

        // Optional: similar cars
        $similarCars = CarListing::where('make', $car->make)
            ->where('id', '!=', $car->id)
            ->latest()
            ->take(4)
            ->get();
        
        
        $about = $this->generateAbout($car);

        return view('web.car-details', compact('car','similarCars','about'));
    }

    private function generateAbout($car)
    {
        $title = "{$car->year} {$car->make} {$car->model} {$car->variant}";
        $mileage = $car->km_driven ? number_format($car->km_driven) . " km driven" : "";
        $fuel = $car->fuel_type ?? "";
        $transmission = $car->transmission ?? "";
        $color = $car->color ? "finished in elegant {$car->color}" : "";
        $ownership = $car->owner_count ? "{$car->owner_count} owner vehicle" : "";

        // Feature Highlights
        $features = [];

        if($car->has_sunroof) $features[] = "sunroof";
        if($car->has_navigation) $features[] = "navigation system";
        if($car->has_reverse_camera) $features[] = "reverse camera";
        if($car->has_parking_sensor) $features[] = "parking sensors";
        if($car->has_airbags) $features[] = "airbags";
        if($car->has_abs) $features[] = "ABS";
        if($car->has_esp) $features[] = "ESP stability control";

        $featureText = count($features)
            ? "Key highlights include " . implode(', ', $features) . "."
            : "";

        // Paragraph 1 – Introduction
        $para1 = "Experience the performance and reliability of this {$title}, {$color}. 
        Powered by a {$fuel} engine paired with a {$transmission} transmission, 
        this {$ownership} has {$mileage}, offering the perfect balance of efficiency and performance.";

        // Paragraph 2 – Comfort & Technology
        $para2 = "Designed for comfort and everyday practicality, this vehicle delivers a smooth driving experience 
        with modern features and premium interior quality. {$featureText} 
        Whether for city drives or highway journeys, it ensures safety and confidence on every trip.";

        // Paragraph 3 – Value & Closing
        $para3 = "Well maintained and competitively priced, this {$car->make} {$car->model} offers exceptional value 
        for anyone looking for a dependable and stylish vehicle. A smart investment that combines performance, 
        comfort, and long-term reliability.";

        return "<p>{$para1}</p><p>{$para2}</p><p>{$para3}</p>";
    }


}
