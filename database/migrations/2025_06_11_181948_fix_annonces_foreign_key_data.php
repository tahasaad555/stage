<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, let's see what we're dealing with
        $orphanedAnnonces = DB::table('annonces')
            ->whereNotIn('fournisseur_id', DB::table('fournisseurs')->pluck('id'))
            ->get();

        if ($orphanedAnnonces->count() > 0) {
            echo "Found {$orphanedAnnonces->count()} orphaned annonces\n";
            
            // Option 1: Delete orphaned annonces
            DB::table('annonces')
                ->whereNotIn('fournisseur_id', DB::table('fournisseurs')->pluck('id'))
                ->delete();
            
            echo "Deleted orphaned annonces\n";
        }

        // Handle NULL fournisseur_id values
        $nullFournisseurAnnonces = DB::table('annonces')->whereNull('fournisseur_id')->get();
        
        if ($nullFournisseurAnnonces->count() > 0) {
            echo "Found {$nullFournisseurAnnonces->count()} annonces with NULL fournisseur_id\n";
            
            // Option 1: Delete them
            DB::table('annonces')->whereNull('fournisseur_id')->delete();
            
            // Option 2: Or assign them to the first available fournisseur
            // $firstFournisseur = DB::table('fournisseurs')->first();
            // if ($firstFournisseur) {
            //     DB::table('annonces')
            //         ->whereNull('fournisseur_id')
            //         ->update(['fournisseur_id' => $firstFournisseur->id]);
            // }
        }
    }

    public function down()
    {
        // Can't really undo data deletion
    }
};