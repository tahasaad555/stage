<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->foreignId('terre_agricole_id')->constrained('terres_agricoles')->onDelete('cascade');
            $table->string('titre');
            $table->string('description');
            $table->date('dateCreation');
            $table->boolean('estActive')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('annonces');
    }
};