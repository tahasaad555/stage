<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavorisTable extends Migration
{
    public function up()
    {
        Schema::create('favoris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('terrain_id')->nullable()->constrained('terrains_agricoles')->onDelete('cascade');
            $table->foreignId('equipment_id')->nullable()->constrained('materiels_fermier_agricoles')->onDelete('cascade');
            $table->string('type'); // 'terrain' or 'equipment'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('favoris');
    }
}