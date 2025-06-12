<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('annonces', function (Blueprint $table) {
            // Ensure columns are correct type
            $table->unsignedBigInteger('fournisseur_id')->nullable()->change();
            $table->unsignedBigInteger('terre_agricole_id')->nullable()->change();
        });

        // Add foreign keys in separate statement
        Schema::table('annonces', function (Blueprint $table) {
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('set null');
            $table->foreign('terre_agricole_id')->references('id')->on('terres_agricoles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('annonces', function (Blueprint $table) {
            $table->dropForeign(['fournisseur_id']);
            $table->dropForeign(['terre_agricole_id']);
        });
    }
};