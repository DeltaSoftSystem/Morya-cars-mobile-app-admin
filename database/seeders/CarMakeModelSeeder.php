<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarMake;
use App\Models\CarModel;

class CarMakeModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $makesModels = [
            'Maruti Suzuki' => ['Alto', 'Swift', 'Dzire', 'Baleno', 'Vitara Brezza', 'Ertiga', 'Celerio', 'S-Presso', 'WagonR', 'Eeco'],
            'Hyundai' => ['i10', 'i20', 'Creta', 'Verna', 'Venue', 'Santro', 'Grand i10 Nios', 'Aura', 'Elantra', 'Tucson'],
            'Tata' => ['Tiago', 'Tigor', 'Nexon', 'Harrier', 'Safari', 'Punch', 'Altroz', 'Hexa', 'Zest', 'Bolt'],
            'Mahindra' => ['Bolero', 'Scorpio', 'Thar', 'XUV700', 'XUV300', 'Marazzo', 'Alturas G4', 'KUV100', 'TUV300'],
            'Kia' => ['Seltos', 'Sonet', 'Carens', 'Carnival', 'EV6', 'Seltos X-Line'],
            'Toyota' => ['Innova Crysta', 'Fortuner', 'Urban Cruiser', 'Glanza', 'Camry', 'Corolla Altis', 'Yaris'],
            'Honda' => ['City', 'Amaze', 'WR-V', 'Jazz', 'Civic', 'BR-V'],
            'MG' => ['Hector', 'Astor', 'ZS EV', 'Gloster', 'Comet EV'],
            'Skoda' => ['Rapid', 'Slavia', 'Kushaq', 'Octavia', 'Superb'],
            'Volkswagen' => ['Polo', 'Virtus', 'Taigun', 'T-Roc', 'Tiguan'],
            'Renault' => ['Kwid', 'Triber', 'Kiger', 'Duster', 'Lodgy'],
            'Nissan' => ['Magnite', 'Kicks', 'Terrano'],
            'Ford' => ['EcoSport', 'Endeavour', 'Figo', 'Freestyle', 'Mustang'],
            'BMW' => ['3 Series', '5 Series', '7 Series', 'X1', 'X3', 'X5', 'X7', 'iX1'],
            'Mercedes-Benz' => ['A-Class', 'C-Class', 'E-Class', 'S-Class', 'GLA', 'GLC', 'GLS', 'G-Class', 'EQB'],
            'Audi' => ['A3', 'A4', 'A6', 'A8', 'Q2', 'Q3', 'Q5', 'Q7', 'Q8', 'e-tron'],
            'Jaguar' => ['XE', 'XF', 'XJ', 'F-Type', 'F-Pace', 'E-Pace'],
            'Land Rover' => ['Discovery', 'Range Rover', 'Evoque', 'Defender'],
            'Porsche' => ['Macan', 'Cayenne', '911'],
            'Volvo' => ['XC40', 'XC60', 'XC90', 'S60', 'S90'],
            'Mitsubishi' => ['Outlander', 'Pajero Sport'],
            'Fiat' => ['Punto', 'Linea'],
            'Honda Motorcycle & Scooter India' => ['Activa', 'CB Shine', 'CB Unicorn'],
            'Hero MotoCorp' => ['Splendor', 'Passion', 'HF Deluxe'],
            'Bajaj Auto' => ['Pulsar', 'Dominar'],
            'TVS Motor Company' => ['Apache', 'Jupiter'],
            'Royal Enfield' => ['Classic', 'Bullet', 'Meteor'],
            'Suzuki Motorcycle India' => ['Gixxer', 'Access'],
            'Yamaha Motor India' => ['FZ', 'R15'],
            'Piaggio India' => ['Vespa'],
            'Tesla' => ['Model 3', 'Model Y', 'Model S', 'Model X'],
            'BYD' => ['Atto 3', 'Tang EV'],
            'Mahindra Electric' => ['eVerito', 'eXUV300', 'eKUV100'],
            'Tata Electric' => ['Tigor EV', 'Nexon EV'],
            'MG Electric' => ['ZS EV'],
            'Kia Electric' => ['EV6'],
            'Toyota Electric' => ['Urban Cruiser Hyryder'],
            'Hyundai Electric' => ['Kona EV'],
            'Honda Electric' => ['e:HEV'],
            'Nissan Electric' => ['Leaf'],
            'BMW Electric' => ['i3', 'i4', 'iX'],
            'Mercedes-Benz Electric' => ['EQC', 'EQA', 'EQB'],
            'Audi Electric' => ['e-tron GT', 'Q4 e-tron'],
            'Skoda Electric' => ['Enyaq iV'],
            'Volkswagen Electric' => ['ID.4'],
            'Volvo Electric' => ['XC40 Recharge'],
            'Porsche Electric' => ['Taycan'],
        ];

        foreach ($makesModels as $makeName => $models) {
            $make = CarMake::firstOrCreate(['name' => $makeName]);
            foreach ($models as $modelName) {
                CarModel::firstOrCreate([
                    'make_id' => $make->id,
                    'name' => $modelName
                ]);
            }
        }
         $this->command->info('Car Makes & Models seeded successfully!');
    }
}
