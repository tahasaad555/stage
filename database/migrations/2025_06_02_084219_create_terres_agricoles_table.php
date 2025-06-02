<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terres_agricoles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('surface', 10, 2);
            $table->decimal('price', 12, 2);
            $table->string('region');
            $table->string('country')->default('France');
            $table->string('gps_coordinates')->nullable();
            $table->string('soil_type')->nullable();
            $table->enum('status', ['available', 'sold', 'reserved'])->default('available');
            $table->json('photos')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terres_agricoles');
    }
};