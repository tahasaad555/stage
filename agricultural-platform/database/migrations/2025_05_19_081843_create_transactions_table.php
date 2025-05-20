<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->date('dateTransaction');
            $table->double('montant');
            $table->double('commission')->default(0);
            $table->string('methodePaiement');
            $table->string('statusTransaction')->default('en_attente'); // en_attente, completee, annulee
            $table->boolean('estVerifiee')->default(false);
            $table->string('referencePaiement')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
