<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\TerreAgricole;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        // ✅ FIXED: Load the complete relationship chain
        $query = Annonce::with(['fournisseur.user', 'terreAgricole']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('titre', 'like', "%{$search}%") // Search both title fields
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('terreAgricole', function($landQuery) use ($search) {
                      $landQuery->where('title', 'like', "%{$search}%")
                               ->orWhere('region', 'like', "%{$search}%");
                  })
                  // ✅ FIXED: Search through the complete relationship chain
                  ->orWhereHas('fournisseur.user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  // ✅ ALSO search in fournisseur fields if they exist
                  ->orWhereHas('fournisseur', function($fournisseurQuery) use ($search) {
                      $fournisseurQuery->where('company_name', 'like', "%{$search}%")
                                      ->orWhere('business_registration', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        // Filter by supplier
        if ($request->filled('fournisseur')) {
            $query->where('fournisseur_id', $request->fournisseur);
        }

        $listings = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total' => Annonce::count(),
            'active' => Annonce::where('is_active', true)->count(),
            'inactive' => Annonce::where('is_active', false)->count(),
            'featured' => Annonce::where('is_featured', true)->count(),
            'published' => Annonce::whereNotNull('published_at')->count(),
        ];

        // ✅ FIXED: Load fournisseurs with their user relationships
        $fournisseurs = Fournisseur::with('user')->get();

        return view('admin.listings.index', compact('listings', 'stats', 'fournisseurs'));
    }

    public function show(Annonce $listing)
    {
        // ✅ FIXED: Load the complete relationship chain
        $listing->load(['fournisseur.user', 'terreAgricole']);
        
        return view('admin.listings.show', compact('listing'));
    }

    public function create()
    {
        $lands = TerreAgricole::where('status', 'available')
                             ->whereDoesntHave('annonce')
                             ->get();
        // ✅ FIXED: Load fournisseurs with their user relationships
        $fournisseurs = Fournisseur::with('user')->get();
        
        return view('admin.listings.create', compact('lands', 'fournisseurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'terre_agricole_id' => 'required|exists:terres_agricoles,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Ensure the land doesn't already have a listing
        $existingListing = Annonce::where('terre_agricole_id', $validated['terre_agricole_id'])->first();
        if ($existingListing) {
            return back()->withErrors(['terre_agricole_id' => 'This land already has a listing.'])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        
        // ✅ ADDED: Store in both title fields for compatibility
        $validated['titre'] = $validated['title'];
        
        if ($request->filled('published_at')) {
            $validated['published_at'] = $request->published_at;
        } elseif ($validated['is_active']) {
            $validated['published_at'] = now();
        }

        Annonce::create($validated);

        return redirect()->route('admin.listings.index')
            ->with('success', 'Listing created successfully.');
    }

    public function edit(Annonce $listing)
    {
        $lands = TerreAgricole::where('status', 'available')
                             ->where(function($query) use ($listing) {
                                 $query->whereDoesntHave('annonce')
                                       ->orWhere('id', $listing->terre_agricole_id);
                             })
                             ->get();
        // ✅ FIXED: Load fournisseurs with their user relationships
        $fournisseurs = Fournisseur::with('user')->get();
        
        return view('admin.listings.edit', compact('listing', 'lands', 'fournisseurs'));
    }

    public function update(Request $request, Annonce $listing)
    {
        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'terre_agricole_id' => 'required|exists:terres_agricoles,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Ensure the land doesn't already have a listing (except this one)
        $existingListing = Annonce::where('terre_agricole_id', $validated['terre_agricole_id'])
                                 ->where('id', '!=', $listing->id)
                                 ->first();
        if ($existingListing) {
            return back()->withErrors(['terre_agricole_id' => 'This land already has a listing.'])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        
        // ✅ ADDED: Store in both title fields for compatibility
        $validated['titre'] = $validated['title'];
        
        if ($request->filled('published_at')) {
            $validated['published_at'] = $request->published_at;
        } elseif ($validated['is_active'] && !$listing->published_at) {
            $validated['published_at'] = now();
        }

        $listing->update($validated);

        return redirect()->route('admin.listings.show', $listing)
            ->with('success', 'Listing updated successfully.');
    }

    public function destroy(Annonce $listing)
    {
        $listing->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Listing deleted successfully.'
            ]);
        }

        return redirect()->route('admin.listings.index')
            ->with('success', 'Listing deleted successfully.');
    }

    public function toggleStatus(Annonce $listing)
    {
        $listing->update([
            'is_active' => !$listing->is_active,
            'published_at' => !$listing->is_active && !$listing->published_at ? now() : $listing->published_at
        ]);

        $status = $listing->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => "Listing {$status} successfully.",
            'status' => $listing->is_active
        ]);
    }

    public function toggleFeatured(Annonce $listing)
    {
        $listing->update(['is_featured' => !$listing->is_featured]);

        $status = $listing->is_featured ? 'featured' : 'unfeatured';

        return response()->json([
            'success' => true,
            'message' => "Listing {$status} successfully.",
            'featured' => $listing->is_featured
        ]);
    }
}