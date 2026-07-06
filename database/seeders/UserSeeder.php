<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Expedition;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'email'    => 'admin@charmonti.com',
            'password' => Hash::make('password'),
        ]);
        $admin->profile()->create([
            'name' => 'Super Admin',
            'role' => 'admin',
        ]);

        $owner = User::create([
            'email'    => 'owner@charmonti.com',
            'password' => Hash::make('Owner123!'),
        ]);
        $owner->profile()->create([
            'name' => 'Owner Charm Onti',
            'role' => 'owner',
        ]);

        $customer = User::create([
            'email'    => 'customer@charmonti.com',
            'password' => Hash::make('password'),
        ]);
        $customer->profile()->create([
            'name' => 'Customer Test',
            'role' => 'customer',
        ]);

        // Seed expedisi default
        Expedition::insert([
            ['name_expedition' => 'JNE', 'created_at' => now(), 'updated_at' => now()],
            ['name_expedition' => 'J&T', 'created_at' => now(), 'updated_at' => now()],
            ['name_expedition' => 'SiCepat', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

