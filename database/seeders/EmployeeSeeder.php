<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\LocationPoint;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $location = LocationPoint::first();

        Employee::create([
            'name' => 'Иван Иванов',
            'phone' => '+70000000001',
            'location_id' => $location->id,
            'specialization' => 'Мойщик',
        ]);

        Employee::create([
            'name' => 'Петр Петров',
            'phone' => '+70000000002',
            'location_id' => $location->id,
            'specialization' => 'Мойщик',
        ]);
    }
}
