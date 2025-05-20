<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produits_agricoles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('description');
            $table->string('categorie');
            $table->double('prix')->default(0);
            $table->integer('quantite')->default(0);
            $table->string('marque')->nullable();
            $table->string('unite')->nullable();
            $table->string('formule')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produits_agricoles');
    }
};