<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('annonces', function (Blueprint $table) {
            if (!Schema::hasColumn('annonces', 'titre')) {
                $table->string('titre')->nullable()->after('title');
            }
            if (!Schema::hasColumn('annonces', 'prix')) {
                $table->decimal('prix', 10, 2)->nullable()->after('description');
            }
        });
    }

    public function down()
    {
        Schema::table('annonces', function (Blueprint $table) {
            $table->dropColumn(['titre', 'prix']);
        });
    }
};