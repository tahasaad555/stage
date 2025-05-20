<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ligne_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panier_id')->constrained('paniers')->onDelete('cascade');
            $table->foreignId('produit_id')->nullable()->constrained('produits_agricoles')->onDelete('set null');
            $table->foreignId('terre_id')->nullable()->constrained('terres_agricoles')->onDelete('set null');
            $table->integer('quantite')->default(1);
            $table->double('prixUnitaire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_commandes');
    }
};