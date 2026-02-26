<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\CarListing;
use App\Models\CarMake;
use App\Models\CarModel;

class CarSyncController extends Controller
{
    public function sync()
    {
        $response = Http::timeout(30)
            ->get('https://app.moryacars.in/api/v1/export/cars');

        if (!$response->successful()) {
            return back()->with('error', 'Failed to fetch cars from source');
        }

        $cars = $response->json('cars');

        $syncedRegNos = [];
        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($cars as $car) {

            // 1️⃣ Make
            $make = CarMake::firstOrCreate([
                'name' => trim($car['brand'])
            ]);

            // 2️⃣ Model
            $model = CarModel::firstOrCreate([
                'make_id' => $make->id,
                'name' => trim($car['model'])
            ]);

            // 3️⃣ Year
            $year = null;
            if (!empty($car['regDate'])) {
                $year = substr($car['regDate'], 0, 4);
            } elseif (!empty($car['mfgDate'])) {
                $year = substr($car['mfgDate'], 0, 4);
            }

            // 4️⃣ Check existing car by reg no
            $listing = CarListing::where('registration_number', $car['regNo'])->first();

            if ($listing) {

                // ❌ DO NOT TOUCH non-pending cars
                if ($listing->status !== 'pending') {
                    $skipped++;
                    continue;
                }

                // ✅ UPDATE only pending cars
                $listing->update([
                    'make_id' => $make->id,
                    'model_id' => $model->id,
                    'make' => $make->name,
                    'model' => $model->name,
                    'title' => $make->name . ' ' . $model->name,
                    'variant' => $car['variant'],
                    'year' => $year,
                    'km_driven' => $car['usedKm'],
                    'fuel_type' => $car['fuelType'],
                    'color' => $car['color'],
                    'owner_count' => $car['noOfOwner'],
                    'price' => $car['sellingPrice'],
                    'expected_price' => $car['discountPrice'],
                    'insurance_company' => $car['insurance'],
                    'insurance_upto' => $car['insuranceValidity'],
                ]);

                $updated++;

            } else {

                // ✅ INSERT new car as PENDING
                CarListing::create([
                    'user_id' => 1,
                    'registration_number' => $car['regNo'],
                    'make_id' => $make->id,
                    'model_id' => $model->id,
                    'make' => $make->name,
                    'model' => $model->name,
                    'title' => $make->name . ' ' . $model->name,
                    'variant' => $car['variant'],
                    'year' => $year,
                    'km_driven' => $car['usedKm'],
                    'fuel_type' => $car['fuelType'],
                    'color' => $car['color'],
                    'owner_count' => $car['noOfOwner'],
                    'price' => $car['sellingPrice'],
                    'expected_price' => $car['discountPrice'],
                    'insurance_company' => $car['insurance'],
                    'insurance_upto' => $car['insuranceValidity'],
                    'status' => 'pending',
                    'sale_status' => 'available',
                ]);

                $inserted++;
            }

            $syncedRegNos[] = $car['regNo'];
        }

        // ❌ IMPORTANT: Do NOT auto-sell approved cars
        CarListing::where('user_id', 1)
            ->whereNotIn('registration_number', $syncedRegNos)
            ->where('status', 'pending')
            ->update([
                'sale_status' => 'sold',
                'sold_at' => now()
            ]);

        return back()->with(
            'success',
            "Sync done. Inserted: $inserted, Updated: $updated, Skipped (locked): $skipped"
        );
    }


}
