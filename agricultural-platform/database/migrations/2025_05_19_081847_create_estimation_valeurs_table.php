<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estimation_valeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terre_agricole_id')->constrained('terres_agricoles')->onDelete('cascade');
            $table->date('dateEstimation');
            $table->json('criteres'); // Store as JSON
            $table->double('valeurEstimee');
            $table->double('calculeDifference');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estimation_valeurs');
    }
};
