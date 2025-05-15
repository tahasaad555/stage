<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->integer('note');
            $table->string('commentaire');
            $table->unsignedBigInteger('clientId');
            $table->unsignedBigInteger('terrainId');
            $table->datetime('dateSoumission');
            $table->foreign('clientId')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('terrainId')->references('id')->on('terrains_agricoles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};