<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('appointment_statuses')->insertOrIgnore([
            ['id' => 1, 'name' => 'Ожидает подтверждения', 'color' => 'light'],
            ['id' => 2, 'name' => 'Подтверждена', 'color' => 'green'],
            ['id' => 3, 'name' => 'Завершена', 'color' => 'blue'],
            ['id' => 4, 'name' => 'Отменена', 'color' => 'red'],
            ['id' => 5, 'name' => 'Активна', 'color' => 'purple'],
        ]);
    }
}
