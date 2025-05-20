<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('demande_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('annonce_id')->constrained('annonces')->onDelete('cascade');
            $table->string('sujet');
            $table->string('contenu');
            $table->string('reponseMessage')->nullable();
            $table->boolean('repondu')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demande_informations');
    }
};