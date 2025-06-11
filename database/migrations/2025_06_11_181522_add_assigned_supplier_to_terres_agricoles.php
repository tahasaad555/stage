<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('terres_agricoles', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_supplier_id')->nullable()->after('status');
            $table->foreign('assigned_supplier_id')->references('id')->on('fournisseurs')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('terres_agricoles', function (Blueprint $table) {
            $table->dropForeign(['assigned_supplier_id']);
            $table->dropColumn('assigned_supplier_id');
        });
    }
};