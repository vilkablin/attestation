<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promocode_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#6b7280');
            $table->timestamps();
        });

        // Стандартные статусы
        DB::table('promocode_statuses')->insert([
            ['id' => 1, 'name' => 'Активен', 'color' => '#10b981'],
            ['id' => 2, 'name' => 'Неактивен', 'color' => '#ef4444'],
            ['id' => 3, 'name' => 'Истек', 'color' => '#6b7280'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('promocode_statuses');
    }
};
