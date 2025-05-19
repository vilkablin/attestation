<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromocodeStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('promocode_statuses')->insertOrIgnore([
            ['id' => 1, 'name' => 'Активен', 'color' => '#10b981'],
            ['id' => 2, 'name' => 'Неактивен', 'color' => '#ef4444'],
            ['id' => 3, 'name' => 'Истек', 'color' => '#6b7280'],
        ]);
    }
}
