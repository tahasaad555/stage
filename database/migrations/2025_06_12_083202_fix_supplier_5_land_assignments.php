<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Fix assignments for supplier ID 5 based on their existing listings
        $landIds = DB::table('annonces')
            ->where('fournisseur_id', 5)
            ->distinct()
            ->pluck('terre_agricole_id');

        foreach ($landIds as $landId) {
            DB::table('terres_agricoles')
                ->where('id', $landId)
                ->update([
                    'assigned_supplier_id' => 5,
                    'updated_at' => now()
                ]);
        }

        echo "Assigned " . count($landIds) . " lands to supplier ID 5\n";
    }

    public function down()
    {
        // Reset assignments for lands that were assigned to supplier 5
        DB::table('terres_agricoles')
            ->where('assigned_supplier_id', 5)
            ->update([
                'assigned_supplier_id' => null,
                'updated_at' => now()
            ]);
    }
};