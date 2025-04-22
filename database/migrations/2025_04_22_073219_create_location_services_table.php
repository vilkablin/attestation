<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('location_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('location_points');
            $table->foreignId('service_id')->constrained();
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->unique(['location_id', 'service_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('location_services');
    }
};
