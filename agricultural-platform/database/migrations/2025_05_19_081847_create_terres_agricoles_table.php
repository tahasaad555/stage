<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('terres_agricoles', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('description');
            $table->double('surface');
            $table->double('prix');
            $table->string('region');
            $table->string('pays');
            $table->string('coordonneesGPS')->nullable();
            $table->string('typeSol')->nullable();
            $table->string('status')->default('disponible');
            $table->string('photos')->nullable(); // JSON string to store multiple photo paths
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('terres_agricoles');
    }
};
