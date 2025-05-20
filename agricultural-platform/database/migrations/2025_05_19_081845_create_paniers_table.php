<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paniers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->dateTime('dateCreation');
            $table->double('total')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paniers');
    }
};