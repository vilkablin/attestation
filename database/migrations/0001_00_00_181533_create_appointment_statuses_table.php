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
            $table->string('color')->default('light');
            $table->timestamps();
        });

        // Стандартные статусы
        DB::table('appointment_statuses')->insert([
            ['id' => 1, 'name' => 'Ожидает подтверждения', 'color' => 'light'],
            ['id' => 2, 'name' => 'Подтверждена', 'color' => 'green'],
            ['id' => 3, 'name' => 'Завершена', 'color' => 'blue'],
            ['id' => 4, 'name' => 'Отменена', 'color' => 'red'],
            ['id' => 5, 'name' => 'Активна', 'color' => 'purple'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('appointment_statuses');
    }
};
