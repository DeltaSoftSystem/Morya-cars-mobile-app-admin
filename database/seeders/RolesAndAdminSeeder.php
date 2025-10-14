<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $roles = ['Admin','Management'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create Super Admin user (change credentials after)
        $admin = User::firstOrCreate(
            ['email' => 'jha@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('jha@123') // change immediately
            ]
        );

        $admin->assignRole('Admin');

    }
}
