<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Clean orphaned annonces (remove echo statements)
        DB::table('annonces')
            ->whereNotIn('fournisseur_id', DB::table('fournisseurs')->pluck('id'))
            ->delete();

        // Clean NULL fournisseur_id annonces
        DB::table('annonces')->whereNull('fournisseur_id')->delete();
    }

    public function down()
    {
        // Cannot undo data deletion
    }
};