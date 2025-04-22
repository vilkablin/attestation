<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#6b7280');
            $table->timestamps();
        });

        // Стандартные статусы
        DB::table('appointment_statuses')->insert([
            ['id' => 1, 'name' => 'Ожидает подтверждения', 'color' => '#f59e0b'],
            ['id' => 2, 'name' => 'Подтверждена', 'color' => '#10b981'],
            ['id' => 3, 'name' => 'Завершена', 'color' => '#3b82f6'],
            ['id' => 4, 'name' => 'Отменена', 'color' => '#ef4444'],
            ['id' => 5, 'name' => 'Активна', 'color' => '#8b5cf6'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('appointment_statuses');
    }
};
