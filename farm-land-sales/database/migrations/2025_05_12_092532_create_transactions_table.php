// Update database/migrations/2025_05_12_092532_create_transactions_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->datetime('dateTransaction');
            $table->double('montant');
            $table->string('statut');
            $table->string('methodePaiement')->nullable();
            $table->double('commission')->nullable();
            $table->unsignedBigInteger('annonceId')->nullable();
            $table->unsignedBigInteger('clientId');
            $table->foreign('annonceId')->references('id')->on('annonces')->onDelete('set null');
            $table->foreign('clientId')->references('id')->on('clients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};