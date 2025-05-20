<?php

namespace App\Http\Controllers;

use App\Models\TerreAgricole;
use App\Models\SystemCartographie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TerreAgricoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TerreAgricole::query();
        
        // Apply filters if provided
        if ($request->has('region')) {
            $query->where('region', $request->region);
        }
        
        if ($request->has('prix_min') && $request->has('prix_max')) {
            $query->whereBetween('prix', [$request->prix_min, $request->prix_max]);
        } elseif ($request->has('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        } elseif ($request->has('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }
        
        if ($request->has('surface_min') && $request->has('surface_max')) {
            $query->whereBetween('surface', [$request->surface_min, $request->surface_max]);
        } elseif ($request->has('surface_min')) {
            $query->where('surface', '>=', $request->surface_min);
        } elseif ($request->has('surface_max')) {
            $query->where('surface', '<=', $request->surface_max);
        }
        
        $terresAgricoles = $query->paginate(12);
        
        return view('terres.index', compact('terresAgricoles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('terres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'surface' => 'required|numeric|min:0',
            'prix' => 'required|numeric|min:0',
            'region' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'coordonneesGPS' => 'nullable|string|max:255',
            'typeSol' => 'nullable|string|max:255',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'zoomLevel' => 'nullable|integer',
            'typeMap' => 'nullable|string|max:255',
        ]);

        // Handle photos upload
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('terres', 'public');
                $photos[] = $path;
            }
        }

        // Create terre agricole
        $terreAgricole = TerreAgricole::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'surface' => $request->surface,
            'prix' => $request->prix,
            'region' => $request->region,
            'pays' => $request->pays,
            'coordonneesGPS' => $request->coordonneesGPS,
            'typeSol' => $request->typeSol,
            'status' => 'disponible',
            'photos' => $photos,
        ]);

        // Create system cartographie if coordinates are provided
        if ($request->coordonneesGPS) {
            SystemCartographie::create([
                'terre_agricole_id' => $terreAgricole->id,
                'coordonneesGPS' => $request->coordonneesGPS,
                'zoomLevel' => $request->zoomLevel ?? 10,
                'typeMap' => $request->typeMap ?? 'satellite',
                'superficie' => $request->surface,
            ]);
        }

        return redirect()->route('terres.index')
            ->with('success', 'Terre agricole créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TerreAgricole $terre)
    {
        $terre->load(['systemCartographie', 'annonce', 'annonce.fournisseur', 'annonce.fournisseur.utilisateur']);
        return view('terres.show', compact('terre'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TerreAgricole $terre)
    {
        $terre->load('systemCartographie');
        return view('terres.edit', compact('terre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TerreAgricole $terre)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'surface' => 'required|numeric|min:0',
            'prix' => 'required|numeric|min:0',
            'region' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'coordonneesGPS' => 'nullable|string|max:255',
            'typeSol' => 'nullable|string|max:255',
            'status' => 'required|string|in:disponible,vendu,reserve',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'zoomLevel' => 'nullable|integer',
            'typeMap' => 'nullable|string|max:255',
        ]);

        // Handle photos upload
        $photos = $terre->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('terres', 'public');
                $photos[] = $path;
            }
        }

        // Update terre agricole
        $terre->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'surface' => $request->surface,
            'prix' => $request->prix,
            'region' => $request->region,
            'pays' => $request->pays,
            'coordonneesGPS' => $request->coordonneesGPS,
            'typeSol' => $request->typeSol,
            'status' => $request->status,
            'photos' => $photos,
        ]);

        // Update or create system cartographie
        if ($request->coordonneesGPS) {
            if ($terre->systemCartographie) {
                $terre->systemCartographie->update([
                    'coordonneesGPS' => $request->coordonneesGPS,
                    'zoomLevel' => $request->zoomLevel ?? $terre->systemCartographie->zoomLevel,
                    'typeMap' => $request->typeMap ?? $terre->systemCartographie->typeMap,
                    'superficie' => $request->surface,
                ]);
            } else {
                SystemCartographie::create([
                    'terre_agricole_id' => $terre->id,
                    'coordonneesGPS' => $request->coordonneesGPS,
                    'zoomLevel' => $request->zoomLevel ?? 10,
                    'typeMap' => $request->typeMap ?? 'satellite',
                    'superficie' => $request->surface,
                ]);
            }
        }

        return redirect()->route('terres.index')
            ->with('success', 'Terre agricole mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TerreAgricole $terre)
    {
        // Delete photos from storage
        if (!empty($terre->photos)) {
            foreach ($terre->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        // Delete related system cartographie
        if ($terre->systemCartographie) {
            $terre->systemCartographie->delete();
        }

        // Delete the terre agricole
        $terre->delete();

        return redirect()->route('terres.index')
            ->with('success', 'Terre agricole supprimée avec succès.');
    }

    /**
     * Remove a specific photo from a terre agricole.
     */
    public function removePhoto(Request $request, TerreAgricole $terre)
    {
        $request->validate([
            'photo_index' => 'required|integer|min:0',
        ]);

        $photos = $terre->photos;
        $index = $request->photo_index;

        if (isset($photos[$index])) {
            // Delete the photo from storage
            Storage::disk('public')->delete($photos[$index]);

            // Remove the photo from the array
            array_splice($photos, $index, 1);

            // Update the terre agricole
            $terre->photos = $photos;
            $terre->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Photo not found.'], 404);
    }
    
    /**
     * Search terres agricoles by criteria
     */
    public function search(Request $request)
    {
        $query = TerreAgricole::query();
        
        // Apply search criteria
        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('titre', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('region', 'like', "%{$keyword}%")
                  ->orWhere('pays', 'like', "%{$keyword}%");
            });
        }
        
        if ($request->has('region')) {
            $query->where('region', $request->region);
        }
        
        if ($request->has('pays')) {
            $query->where('pays', $request->pays);
        }
        
        if ($request->has('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }
        
        if ($request->has('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }
        
        if ($request->has('surface_min')) {
            $query->where('surface', '>=', $request->surface_min);
        }
        
        if ($request->has('surface_max')) {
            $query->where('surface', '<=', $request->surface_max);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('typeSol')) {
            $query->where('typeSol', $request->typeSol);
        }
        
        // Store search criteria in user's recherche table if user is logged in and is a client
        if (auth()->check() && auth()->user()->isClient()) {
            $client = auth()->user()->client;
            $client->rechercheTerreAgricoles()->create([
                'criteres' => $request->except('_token'),
            ]);
        }
        
        $terresAgricoles = $query->paginate(12);
        
        return view('terres.search-results', compact('terresAgricoles', 'request'));
    }
}