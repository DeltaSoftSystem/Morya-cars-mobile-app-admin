<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarListing;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\User;

class CarListingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $makes = CarMake::with('models')->get();
        $fuelTypes = ['Petrol', 'Diesel', 'CNG', 'Electric'];
        $statuses = ['pending', 'approved', 'rejected'];
        $years = range(2000, date('Y'));
        $colors = ['Red','Blue','White','Black','Silver','Grey'];
        $bodyTypes = ['Hatchback','Sedan','SUV','MPV','Coupe','Convertible'];

        // Map of sample cities -> [state, lat, lng]
        $cities = [
            'Mumbai'    => ['MH', 19.0760, 72.8777],
            'Delhi'     => ['DL', 28.7041, 77.1025],
            'Bangalore' => ['KA', 12.9716, 77.5946],
            'Chennai'   => ['TN', 13.0827, 80.2707],
            'Lucknow'   => ['UP', 26.8467, 80.9462],
            'Ahmedabad' => ['GJ', 23.0225, 72.5714],
            // add more cities here if desired
        ];

        // Create 200 sample car listings
        for ($i = 1; $i <= 200; $i++) {
            $user = $users->random();

            $make = $makes->random();
            if ($make->models->isEmpty()) continue;
            $model = $make->models->random();

            // choose registration city randomly (from keys of $cities)
            $cityKeys = array_keys($cities);
            $regCity = $cityKeys[array_rand($cityKeys)];
            $regState = $cities[$regCity][0];

            // generate coords: base + jitter
            $baseLat = $cities[$regCity][1];
            $baseLng = $cities[$regCity][2];

            // jitter in degrees (~±0.1 => ~±11km). Adjust denominator for bigger/smaller spread.
            $lat = $baseLat + (mt_rand(-1000, 1000) / 10000);
            $lng = $baseLng + (mt_rand(-1000, 1000) / 10000);

            CarListing::create([
                'user_id' => $user->id,
                'make_id' => $make->id,
                'model_id' => $model->id,
                'make' => $make->name,
                'model' => $model->name,
                'title' => $make->name . ' ' . $model->name . ' ' . rand(1,3) . ' Owner',
                'variant' => 'Standard',
                'year' => $years[array_rand($years)],
                'km_driven' => rand(5000, 150000),
                'fuel_type' => $fuelTypes[array_rand($fuelTypes)],
                'transmission' => rand(0,1) ? 'Manual' : 'Automatic',
                'body_type' => $bodyTypes[array_rand($bodyTypes)],
                'color' => $colors[array_rand($colors)],
                'price' => rand(200000, 2500000),
                'expected_price' => rand(200000, 2500000),
                'is_negotiable' => rand(0,1),
                'owner_count' => rand(1,4),
                'registration_state' => $regState,
                'registration_city' => $regCity,
                'registration_number' => 'MH' . str_pad((string)rand(1,99), 2, '0', STR_PAD_LEFT) . ' ' . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2)) . ' ' . rand(1000,9999),
                'status' => $statuses[array_rand($statuses)],
                'has_sunroof' => rand(0,1),
                'has_navigation' => rand(0,1),
                'has_parking_sensor' => rand(0,1),
                'has_reverse_camera' => rand(0,1),
                'has_airbags' => rand(0,1),
                'has_abs' => rand(0,1),
                'has_esp' => rand(0,1),
                'inspection_report_url' => null,
                'inspection_summary' => null,
                'is_featured' => rand(0,1),
                'views_count' => rand(0,500),
                'leads_count' => rand(0,100),

                // location fields
                'location_city' => $regCity,
                'location_state' => $regState,
                'latitude' => $lat,
                'longitude' => $lng,
            ]);
        }

        $this->command->info('200 sample car listings created successfully with location data!');
    }
}
