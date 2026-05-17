<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@charmonti.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Owner Charm Onti',
            'email' => 'owner@charmonti.com',
            'password' => Hash::make('Owner123!'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Customer Test',
            'email' => 'customer@charmonti.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}
