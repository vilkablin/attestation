<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AppointmentStatusSeeder::class,
            PromocodeStatusSeeder::class,
            UserSeeder::class,
            ServiceSeeder::class,
            LocationPointSeeder::class,
            EmployeeSeeder::class,
            EmployeeServiceSeeder::class,
            LocationServiceSeeder::class,
            AppointmentSeeder::class,
        ]);
    }
}
