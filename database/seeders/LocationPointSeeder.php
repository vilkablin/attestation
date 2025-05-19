<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LocationPoint;

class LocationPointSeeder extends Seeder
{
    public function run(): void
    {
        LocationPoint::create([
            'address' => 'г.Казань, ул. Чистопольская 75',
            'places_count' => 3,
            'phone' => '+79998887766',
        ]);

        LocationPoint::create([
            'address' => 'г.Казань, ул. Ямашева 82',
            'places_count' => 5,
            'phone' => '+79997776655',
        ]);
    }
}
