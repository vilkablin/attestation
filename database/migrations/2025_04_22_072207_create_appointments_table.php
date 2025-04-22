<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Основные поля
            $table->dateTime('date');
            $table->dateTime('end_date')->nullable();
            $table->text('comment')->nullable();
            $table->decimal('price', 10, 2);

            // Внешние ключи
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('location_points')->cascadeOnDelete();
            $table->foreignId('status_id')->constrained('appointment_statuses');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('promocode_id')->nullable()->constrained('promocodes')->nullOnDelete();

            // Системные поля
            $table->timestamps();
            $table->softDeletes();

            // Индексы
            $table->index('date');
            $table->index(['date', 'location_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
