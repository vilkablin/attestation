<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LocationPoint;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class LocationServiceSeeder extends Seeder
{
    public function run(): void
    {
        $locations = LocationPoint::all();
        $services = Service::all();

        if ($locations->count() && $services->count()) {
            foreach ($locations as $location) {
                foreach ($services as $service) {
                    DB::table('location_services')->insert([
                        'location_id' => $location->id,
                        'service_id' => $service->id,
                        'price' => $service->base_price, // Можно указать свою цену, если нужно
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
