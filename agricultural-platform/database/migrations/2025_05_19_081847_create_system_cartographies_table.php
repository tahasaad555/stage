<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('system_cartographies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terre_agricole_id')->constrained('terres_agricoles')->onDelete('cascade');
            $table->string('coordonneesGPS');
            $table->integer('zoomLevel');
            $table->string('typeMap');
            $table->double('superficie');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_cartographies');
    }
};
