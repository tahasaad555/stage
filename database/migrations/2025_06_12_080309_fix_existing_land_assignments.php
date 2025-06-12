<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration fixes existing land assignments based on existing property listings
     */
    public function up()
    {
        // First, let's see what we're working with
        $totalLands = DB::table('terres_agricoles')->count();
        $assignedLands = DB::table('terres_agricoles')->whereNotNull('assigned_supplier_id')->count();
        $totalListings = DB::table('annonces')->count();
        
        echo "Before migration:\n";
        echo "- Total lands: {$totalLands}\n";
        echo "- Already assigned lands: {$assignedLands}\n";
        echo "- Total listings: {$totalListings}\n";

        // Update terres_agricoles to assign them to suppliers who already have listings for them
        $assignments = DB::table('annonces')
            ->join('terres_agricoles', 'annonces.terre_agricole_id', '=', 'terres_agricoles.id')
            ->select('annonces.fournisseur_id', 'annonces.terre_agricole_id', 'terres_agricoles.title')
            ->whereNull('terres_agricoles.assigned_supplier_id') // Only update unassigned lands
            ->get();

        $updatedCount = 0;
        foreach ($assignments as $assignment) {
            $updated = DB::table('terres_agricoles')
                ->where('id', $assignment->terre_agricole_id)
                ->whereNull('assigned_supplier_id') // Safety check
                ->update([
                    'assigned_supplier_id' => $assignment->fournisseur_id,
                    'updated_at' => now()
                ]);
            
            if ($updated) {
                $updatedCount++;
                echo "✅ Assigned land '{$assignment->title}' (ID: {$assignment->terre_agricole_id}) to supplier {$assignment->fournisseur_id}\n";
            }
        }

        // Final count
        $finalAssignedLands = DB::table('terres_agricoles')->whereNotNull('assigned_supplier_id')->count();
        
        echo "\nAfter migration:\n";
        echo "- Newly assigned lands: {$updatedCount}\n";
        echo "- Total assigned lands: {$finalAssignedLands}\n";
        echo "✅ Land assignment migration completed successfully!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Get count before reversal
        $assignedCount = DB::table('terres_agricoles')->whereNotNull('assigned_supplier_id')->count();
        
        // Reset assignments that were created by this migration
        // We'll only reset assignments where there's a corresponding listing
        $listingBasedAssignments = DB::table('annonces')
            ->join('terres_agricoles', 'annonces.terre_agricole_id', '=', 'terres_agricoles.id')
            ->where('terres_agricoles.assigned_supplier_id', '=', DB::raw('annonces.fournisseur_id'))
            ->pluck('terres_agricoles.id');

        $resetCount = DB::table('terres_agricoles')
            ->whereIn('id', $listingBasedAssignments)
            ->update([
                'assigned_supplier_id' => null,
                'updated_at' => now()
            ]);

        echo "Migration rollback completed. Reset {$resetCount} land assignments.\n";
    }
};