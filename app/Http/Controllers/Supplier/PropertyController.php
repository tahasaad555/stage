<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\TerreAgricole;
use Illuminate\Http\Request;
use App\Models\Fournisseur;

class PropertyController extends Controller
{
    /**
     * Display supplier's properties
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $fournisseur = $user->fournisseur;
        
        if (!$fournisseur) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Supplier profile not found.');
        }

        // Build query for supplier's listings
        $query = Annonce::with(['terreAgricole'])
            ->where('fournisseur_id', $fournisseur->id);

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('terreAgricole', function($landQuery) use ($search) {
                      $landQuery->where('title', 'like', "%{$search}%")
                               ->orWhere('region', 'like', "%{$search}%")
                               ->orWhere('soil_type', 'like', "%{$search}%");
                  });
            });
        }

        $properties = $query->latest()->paginate(12);

        // Calculate statistics with safe defaults
        $totalProperties = $fournisseur->annonces()->count();
        $activeProperties = $fournisseur->annonces()->where('is_active', true)->count();
        $inactiveProperties = $fournisseur->annonces()->where('is_active', false)->count();
        $featuredProperties = $fournisseur->annonces()->where('is_featured', true)->count();
        
        // Calculate total value and surface safely
        $totalValue = $fournisseur->annonces()
            ->join('terres_agricoles', 'annonces.terre_agricole_id', '=', 'terres_agricoles.id')
            ->sum('terres_agricoles.price') ?? 0;
            
        $totalSurface = $fournisseur->annonces()
            ->join('terres_agricoles', 'annonces.terre_agricole_id', '=', 'terres_agricoles.id')
            ->sum('terres_agricoles.surface') ?? 0;

        $stats = [
            'total' => $totalProperties,
            'active' => $activeProperties,
            'inactive' => $inactiveProperties,
            'featured' => $featuredProperties,
            'total_value' => $totalValue,
            'total_surface' => $totalSurface,
        ];

        return view('supplier.properties.index', compact('properties', 'stats'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        $user = auth()->user();
        $fournisseur = $user->fournisseur;
        
        if (!$fournisseur) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Supplier profile not found.');
        }

        // ✅ FIXED: Allow multiple listings per land (sale, rent, different terms)
        // Show all lands assigned to this supplier
        $agriculturalLands = TerreAgricole::where('assigned_supplier_id', $fournisseur->id)
            ->get();
        
        return view('supplier.properties.create', compact('agriculturalLands'));
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $fournisseur = $user->fournisseur;
        
        if (!$fournisseur) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Supplier profile not found.');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'terre_agricole_id' => 'required|exists:terres_agricoles,id',
            'prix' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        // ✅ IMPROVED: Verify the land is assigned to this supplier
        $land = TerreAgricole::where('id', $validated['terre_agricole_id'])
            ->where('assigned_supplier_id', $fournisseur->id)
            ->firstOrFail();

        // ✅ Allow multiple listings but warn about similar ones
        $existingListings = Annonce::where('terre_agricole_id', $validated['terre_agricole_id'])
            ->where('fournisseur_id', $fournisseur->id)
            ->where('is_active', true)
            ->get();

        if ($existingListings->count() > 0) {
            // Add a warning message but allow the creation
            session()->flash('warning', 'You already have ' . $existingListings->count() . ' active listing(s) for this land.');
        }

        // Create the property listing using Annonce model
        $property = Annonce::create([
            'titre' => $validated['titre'],
            'title' => $validated['titre'],
            'description' => $validated['description'],
            'terre_agricole_id' => $validated['terre_agricole_id'],
            'prix' => $validated['prix'],
            'fournisseur_id' => $fournisseur->id,
            'is_active' => $request->boolean('is_active', false),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        // Set published_at if property is active
        if ($property->is_active) {
            $property->published_at = now();
            $property->save();
        }

        return redirect()
            ->route('supplier.properties.index')
            ->with('success', 'Property listing created successfully!');
    }

    /**
     * Show specific property details
     */
    public function show(Annonce $property)
    {
        // Ensure the property belongs to the authenticated supplier
        $user = auth()->user();
        if ($property->fournisseur_id !== $user->fournisseur->id) {
            abort(403, 'Unauthorized access to this property.');
        }

       $property->load(['terreAgricole', 'fournisseur']);
        
        return view('supplier.properties.show', compact('property'));
    }

    /**
     * Update property listing
     */
    public function update(Request $request, Annonce $property)
    {
        // Ensure the property belongs to the authenticated supplier
        $user = auth()->user();
        if ($property->fournisseur_id !== $user->fournisseur->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['title'] = $validated['titre'];
        
        // Set published_at when activating for the first time
        if ($validated['is_active'] && !$property->published_at) {
            $validated['published_at'] = now();
        }

        $property->update($validated);

        return back()->with('success', 'Property updated successfully!');
    }

    /**
     * Toggle property status (active/inactive)
     */
    public function toggleStatus(Annonce $property)
    {
        // Ensure the property belongs to the authenticated supplier
        $user = auth()->user();
        if ($property->fournisseur_id !== $user->fournisseur->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $property->is_active = !$property->is_active;
        
        // Set published_at when activating
        if ($property->is_active && !$property->published_at) {
            $property->published_at = now();
        }
        
        $property->save();

        return response()->json([
            'success' => true,
            'status' => $property->is_active,
            'message' => $property->is_active ? 'Property activated successfully' : 'Property deactivated successfully'
        ]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Annonce $property)
    {
        // Ensure the property belongs to the authenticated supplier
        $user = auth()->user();
        if ($property->fournisseur_id !== $user->fournisseur->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $property->is_featured = !$property->is_featured;
        $property->save();

        return response()->json([
            'success' => true,
            'featured' => $property->is_featured,
            'message' => $property->is_featured ? 'Property featured successfully' : 'Property unfeatured successfully'
        ]);
    }

    /**
     * Delete property listing
     */
    public function destroy(Annonce $property)
    {
        // Ensure the property belongs to the authenticated supplier
        $user = auth()->user();
        if ($property->fournisseur_id !== $user->fournisseur->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        $property->delete();

        return redirect()->route('supplier.properties.index')
            ->with('success', 'Property listing deleted successfully!');
    }
}