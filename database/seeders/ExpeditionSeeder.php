<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expedition;

class ExpeditionSeeder extends Seeder
{
    public function run(): void
    {
        $expeditions = [
            ['id' => 1, 'name_expedition' => 'JNE', 'shipping_cost' => 12000, 'estimated_days' => 2],
            ['id' => 2, 'name_expedition' => 'J&T Express', 'shipping_cost' => 13000, 'estimated_days' => 2],
            ['id' => 3, 'name_expedition' => 'SiCepat', 'shipping_cost' => 12500, 'estimated_days' => 2],
            ['id' => 4, 'name_expedition' => 'POS Indonesia', 'shipping_cost' => 11000, 'estimated_days' => 3],
        ];

        foreach ($expeditions as $exp) {
            Expedition::firstOrCreate(
                ['id' => $exp['id']],
                [
                    'name_expedition' => $exp['name_expedition'],
                    'shipping_cost' => $exp['shipping_cost'],
                    'estimated_days' => $exp['estimated_days'],
                ]
            );
        }
    }
}
