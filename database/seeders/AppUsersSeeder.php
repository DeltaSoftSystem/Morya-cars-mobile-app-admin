<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppUser;

class AppUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         for ($i = 1; $i <= 5; $i++) {
            AppUser::create([
                'name' => "Buyer $i",
                'email' => "buyer$i@example.com",
                'mobile' => "90000000" . $i, // Sample mobile numbers
                'role' => 'buyer',
                'status' => 'active',
                'otp' => rand(100000, 999999),
                'is_mobile_verified' => $i % 2 == 0, // alternate verified
                'is_email_verified' => $i % 2 == 1, // alternate verified
            ]);
        }

        // Sample Sellers
        for ($i = 1; $i <= 5; $i++) {
            AppUser::create([
                'name' => "Seller $i",
                'email' => "seller$i@example.com",
                'mobile' => "91000000" . $i,
                'role' => 'seller',
                'status' => 'active',
                'otp' => rand(100000, 999999),
                'is_mobile_verified' => $i % 2 == 1,
                'is_email_verified' => $i % 2 == 0,
            ]);
        }
    }
}
