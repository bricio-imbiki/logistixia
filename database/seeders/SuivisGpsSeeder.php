<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuivisGps;
use App\Models\Camion;
use Carbon\Carbon;

class SuivisGpsSeeder extends Seeder
{
    public function run(): void
    {
        $camions = Camion::all();

        foreach ($camions as $camion) {
            for ($i = 0; $i < 10; $i++) {
                SuivisGps::create([
                    'camion_id' => $camion->id,
                    'latitude' => -18.1465 + rand(-100, 100) / 10000, // Environ Tamatave
                    'longitude' => 49.4022 + rand(-100, 100) / 10000,
                    'vitesse_kmh' => rand(0, 90),
                    'niveau_carburant' => rand(20, 90),
                    'event_time' => Carbon::now()->subMinutes(10 * $i),
                ]);
            }
        }
    }
}
