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

        // Create 200 sample car listings
        for ($i = 1; $i <= 200; $i++) {
            $user = $users->random();

            $make = $makes->random();
            if ($make->models->isEmpty()) continue;
            $model = $make->models->random();

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
                'body_type' => ['Hatchback','Sedan','SUV','MPV','Coupe','Convertible'][array_rand(['Hatchback','Sedan','SUV','MPV','Coupe','Convertible'])],
                'color' => $colors[array_rand($colors)],
                'price' => rand(200000, 2500000),
                'expected_price' => rand(200000, 2500000),
                'is_negotiable' => rand(0,1),
                'owner_count' => rand(1,4),
                'registration_state' => ['MH','DL','KA','TN','UP','GJ'][array_rand(['MH','DL','KA','TN','UP','GJ'])],
                'registration_city' => ['Mumbai','Delhi','Bangalore','Chennai','Lucknow','Ahmedabad'][array_rand(['Mumbai','Delhi','Bangalore','Chennai','Lucknow','Ahmedabad'])],
                'registration_number' => 'MH' . rand(01,99) . ' ' . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2)) . ' ' . rand(1000,9999),
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
            ]);
        }

        $this->command->info('200 sample car listings created successfully!');
    }
}
