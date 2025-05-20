<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recherches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->json('criteres'); // Store search criteria as JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recherches');
    }
};