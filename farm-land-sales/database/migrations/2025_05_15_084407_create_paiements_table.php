<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->string('methode');
            $table->string('reference')->nullable();
            $table->string('detail')->nullable();
            $table->double('total');
            $table->boolean('verified')->default(false);
            $table->boolean('complet')->default(false);
            $table->unsignedBigInteger('transactionId');
            $table->datetime('dateTime');
            $table->foreign('transactionId')->references('id')->on('transactions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};