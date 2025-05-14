// Update database/migrations/2025_05_12_092532_create_terrains_agricoles_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terrains_agricoles', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->string('adresse');
            $table->double('superficie');
            $table->double('prix');
            $table->string('region')->nullable();
            $table->string('coordonneesGPS')->nullable();
            $table->string('statut')->default('available');
            $table->unsignedBigInteger('proprietaireId');
            $table->string('images')->nullable();
            $table->boolean('visible')->default(true);
            $table->string('type')->nullable();
            $table->foreign('proprietaireId')->references('id')->on('clients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terrains_agricoles');
    }
};