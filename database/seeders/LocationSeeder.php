<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\City;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Provinces
        $provinces = [
            ['id' => 1, 'province' => 'DKI Jakarta'],
            ['id' => 2, 'province' => 'Jawa Barat'],
            ['id' => 3, 'province' => 'Jawa Tengah'],
            ['id' => 4, 'province' => 'Jawa Timur'],
            ['id' => 5, 'province' => 'Banten'],
        ];

        foreach ($provinces as $prov) {
            Province::firstOrCreate(['id' => $prov['id']], ['province' => $prov['province']]);
        }

        // 2. Seed Cities
        $cities = [
            // DKI Jakarta
            ['province_id' => 1, 'city' => 'Jakarta Selatan'],
            ['province_id' => 1, 'city' => 'Jakarta Pusat'],
            ['province_id' => 1, 'city' => 'Jakarta Barat'],
            ['province_id' => 1, 'city' => 'Jakarta Utara'],
            ['province_id' => 1, 'city' => 'Jakarta Timur'],
            // Jawa Barat
            ['province_id' => 2, 'city' => 'Bandung'],
            ['province_id' => 2, 'city' => 'Bekasi'],
            ['province_id' => 2, 'city' => 'Depok'],
            ['province_id' => 2, 'city' => 'Bogor'],
            // Jawa Tengah
            ['province_id' => 3, 'city' => 'Semarang'],
            ['province_id' => 3, 'city' => 'Surakarta (Solo)'],
            // Jawa Timur
            ['province_id' => 4, 'city' => 'Surabaya'],
            ['province_id' => 4, 'city' => 'Malang'],
            // Banten
            ['province_id' => 5, 'city' => 'Tangerang'],
            ['province_id' => 5, 'city' => 'Tangerang Selatan'],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['province_id' => $city['province_id'], 'city' => $city['city']]
            );
        }
    }
}
