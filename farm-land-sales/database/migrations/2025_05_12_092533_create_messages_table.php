// Update database/migrations/2025_05_12_092533_create_messages_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('contenu');
            $table->timestamp('dateEnvoi');
            $table->boolean('lu')->default(false);
            $table->unsignedBigInteger('expediteurId');
            $table->unsignedBigInteger('destinataireId');
            $table->foreign('expediteurId')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('destinataireId')->references('id')->on('clients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};