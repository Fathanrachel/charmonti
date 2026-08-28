<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'email'    => 'admin@charmonti.com',
            'password' => Hash::make('password'),
        ]);
        Profile::create([
            'users_id' => $admin->id,
            'name'     => 'Super Admin',
            'role'     => 'admin',
        ]);

        $owner = User::create([
            'email'    => 'owner@charmonti.com',
            'password' => Hash::make('Owner123!'),
        ]);
        Profile::create([
            'users_id' => $owner->id,
            'name'     => 'Owner Charm Onti',
            'role'     => 'owner',
        ]);

        $customer = User::create([
            'email'    => 'customer@charmonti.com',
            'password' => Hash::make('password'),
        ]);
        Profile::create([
            'users_id' => $customer->id,
            'name'     => 'Customer Test',
            'role'     => 'customer',
        ]);
    }
}
