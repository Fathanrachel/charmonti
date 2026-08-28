<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Expedition;
use App\Models\CityExpedition;

class CityExpeditionSeeder extends Seeder
{
    public function run(): void
    {
        $cities = City::all();
        $expeditions = Expedition::all()->keyBy('name_expedition');

        if ($expeditions->isEmpty()) {
            return;
        }

        foreach ($cities as $city) {
            $provId = $city->province_id;

            // Determine zone-based pricing multiplier/base
            // 1: DKI Jakarta, 2: Jawa Barat, 3: Jawa Tengah, 4: Jawa Timur, 5: Banten, 6: DI Yogyakarta
            // 7-16: Sumatra, 17-19: Bali/Nusa Tenggara, 20-24: Kalimantan, 25-30: Sulawesi, 31-38: Maluku/Papua
            
            $rates = [];

            if ($provId == 1) { // DKI Jakarta
                $rates = [
                    'JNE'           => ['cost' => 9000,  'days' => 1],
                    'J&T Express'   => ['cost' => 10000, 'days' => 1],
                    'SiCepat'       => ['cost' => 9500,  'days' => 1],
                    'POS Indonesia' => ['cost' => 8000,  'days' => 2],
                ];
            } elseif ($provId == 2 || $provId == 5) { // Jabar & Banten
                $rates = [
                    'JNE'           => ['cost' => 12000, 'days' => 2],
                    'J&T Express'   => ['cost' => 13000, 'days' => 2],
                    'SiCepat'       => ['cost' => 12500, 'days' => 2],
                    'POS Indonesia' => ['cost' => 11000, 'days' => 3],
                ];
            } elseif ($provId == 3 || $provId == 6) { // Jateng & DIY
                $rates = [
                    'JNE'           => ['cost' => 17000, 'days' => 2],
                    'J&T Express'   => ['cost' => 18000, 'days' => 2],
                    'SiCepat'       => ['cost' => 17500, 'days' => 2],
                    'POS Indonesia' => ['cost' => 15000, 'days' => 3],
                ];
            } elseif ($provId == 4) { // Jatim
                $rates = [
                    'JNE'           => ['cost' => 21000, 'days' => 2],
                    'J&T Express'   => ['cost' => 22000, 'days' => 2],
                    'SiCepat'       => ['cost' => 20000, 'days' => 2],
                    'POS Indonesia' => ['cost' => 19000, 'days' => 3],
                ];
            } elseif ($provId >= 7 && $provId <= 16) { // Sumatra
                $rates = [
                    'JNE'           => ['cost' => 32000, 'days' => 3],
                    'J&T Express'   => ['cost' => 34000, 'days' => 3],
                    'SiCepat'       => ['cost' => 33000, 'days' => 3],
                    'POS Indonesia' => ['cost' => 28000, 'days' => 4],
                ];
            } elseif ($provId >= 17 && $provId <= 19) { // Bali & Nusa Tenggara
                $rates = [
                    'JNE'           => ['cost' => 30000, 'days' => 3],
                    'J&T Express'   => ['cost' => 32000, 'days' => 3],
                    'SiCepat'       => ['cost' => 31000, 'days' => 3],
                    'POS Indonesia' => ['cost' => 27000, 'days' => 4],
                ];
            } elseif ($provId >= 20 && $provId <= 24) { // Kalimantan
                $rates = [
                    'JNE'           => ['cost' => 40000, 'days' => 4],
                    'J&T Express'   => ['cost' => 42000, 'days' => 4],
                    'SiCepat'       => ['cost' => 41000, 'days' => 4],
                    'POS Indonesia' => ['cost' => 36000, 'days' => 5],
                ];
            } elseif ($provId >= 25 && $provId <= 30) { // Sulawesi
                $rates = [
                    'JNE'           => ['cost' => 43000, 'days' => 4],
                    'J&T Express'   => ['cost' => 45000, 'days' => 4],
                    'SiCepat'       => ['cost' => 44000, 'days' => 4],
                    'POS Indonesia' => ['cost' => 38000, 'days' => 5],
                ];
            } else { // Maluku & Papua (31 - 38)
                $rates = [
                    'JNE'           => ['cost' => 65000, 'days' => 6],
                    'J&T Express'   => ['cost' => 68000, 'days' => 6],
                    'SiCepat'       => ['cost' => 66000, 'days' => 6],
                    'POS Indonesia' => ['cost' => 58000, 'days' => 7],
                ];
            }

            foreach ($rates as $expName => $info) {
                if (isset($expeditions[$expName])) {
                    $expId = $expeditions[$expName]->id;
                    CityExpedition::updateOrCreate(
                        [
                            'city_id' => $city->id,
                            'expedition_id' => $expId,
                        ],
                        [
                            'shipping_cost' => $info['cost'],
                            'estimated_days' => $info['days'],
                        ]
                    );
                }
            }
        }
    }
}
