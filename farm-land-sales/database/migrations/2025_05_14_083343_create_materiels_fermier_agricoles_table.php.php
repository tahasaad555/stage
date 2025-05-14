// Update database/migrations/2025_05_12_092532_create_materiels_fermier_agricoles_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiels_fermier_agricoles', function (Blueprint $table) {
            $table->id();
            $table->string('typeEquipment');
            $table->boolean('estNeuf');
            $table->text('description');
            $table->double('prix');
            $table->string('documentCatalogue')->nullable();
            $table->unsignedBigInteger('fournisseurId');
            $table->unsignedBigInteger('annonceId')->nullable();
            $table->foreign('fournisseurId')->references('id')->on('fournisseurs')->onDelete('cascade');
            $table->foreign('annonceId')->references('id')->on('annonces')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiels_fermier_agricoles');
    }
};