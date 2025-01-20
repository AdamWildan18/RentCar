<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->integer('passenger');
            $table->integer('door');
            $table->enum('gear', ['Manual', 'Automatic']);
            $table->bigInteger('price_per_day');
            $table->string('image')->nullable();
            $table->string('quantity');
            $table->string('status')->default('available');
            $table->integer('reduce');
            $table->integer('stars');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};