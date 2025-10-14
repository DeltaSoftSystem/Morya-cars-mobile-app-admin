<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $plans = [
            ['name' => 'Starter Drive','validity_days'=>30,'features'=>'Basic access, 10 listings','price'=>0],
            ['name' => 'Smart Ride','validity_days'=>90,'features'=>'50 listings, Priority support','price'=>49.99],
            ['name' => 'Turbo Seller','validity_days'=>180,'features'=>'Unlimited listings, Featured badge','price'=>99.99],
        ];

        foreach ($plans as $p) {
            SubscriptionPlan::updateOrCreate(['name'=>$p['name']], $p);
        }
    }
}
