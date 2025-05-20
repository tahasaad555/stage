<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('document_fournisseur_agricoles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->string('typeSol');
            $table->boolean('estCertifie')->default(false);
            $table->string('infrastructures')->nullable();
            $table->string('documentsCadastres')->nullable(); // JSON string to store multiple document paths
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_fournisseur_agricoles');
    }
};