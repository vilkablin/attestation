<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount', 5, 2);
            $table->dateTime('valid_from');
            $table->dateTime('valid_to');
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->text('description')->nullable();
            $table->foreignId('status_id')->default(1)->constrained('promocode_statuses');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            // Индексы
            $table->index('code');
            $table->index(['valid_from', 'valid_to']);
            $table->index('status_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('promocodes');
    }
};
