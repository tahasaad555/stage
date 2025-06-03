<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TerreAgricole;
use Illuminate\Http\Request;

class AgriculturalLandController extends Controller
{
    public function index(Request $request)
    {
        $query = TerreAgricole::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by region
        if ($request->filled('region')) {
            $query->where('region', 'like', "%{$request->region}%");
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $lands = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total' => TerreAgricole::count(),
            'available' => TerreAgricole::where('status', 'available')->count(),
            'sold' => TerreAgricole::where('status', 'sold')->count(),
            'reserved' => TerreAgricole::where('status', 'reserved')->count(),
            'total_surface' => TerreAgricole::sum('surface'),
            'avg_price' => TerreAgricole::avg('price'),
        ];

        // Get unique regions for filter
        $regions = TerreAgricole::distinct()->pluck('region')->filter();

        return view('admin.lands.index', compact('lands', 'stats', 'regions'));
    }

    public function show(TerreAgricole $land)
    {
        $land->load(['annonce.fournisseur.user', 'transactions.client.user']);
        
        return view('admin.lands.show', compact('land'));
    }

    public function create()
    {
        return view('admin.lands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'surface' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'region' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'gps_coordinates' => 'nullable|string|max:255',
            'soil_type' => 'nullable|string|max:255',
            'status' => 'required|in:available,sold,reserved',
        ]);

        TerreAgricole::create($validated);

        return redirect()->route('admin.lands.index')
            ->with('success', 'Agricultural land created successfully.');
    }

    public function edit(TerreAgricole $land)
    {
        return view('admin.lands.edit', compact('land'));
    }

    public function update(Request $request, TerreAgricole $land)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'surface' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'region' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'gps_coordinates' => 'nullable|string|max:255',
            'soil_type' => 'nullable|string|max:255',
            'status' => 'required|in:available,sold,reserved',
        ]);

        $land->update($validated);

        return redirect()->route('admin.lands.show', $land)
            ->with('success', 'Agricultural land updated successfully.');
    }

    public function destroy(TerreAgricole $land)
    {
        // Check if land has any transactions
        if ($land->transactions()->exists()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete land with existing transactions.'
                ], 422);
            }

            return redirect()->route('admin.lands.index')
                ->with('error', 'Cannot delete land with existing transactions.');
        }

        $land->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Agricultural land deleted successfully.'
            ]);
        }

        return redirect()->route('admin.lands.index')
            ->with('success', 'Agricultural land deleted successfully.');
    }

    public function updateStatus(Request $request, TerreAgricole $land)
    {
        $request->validate([
            'status' => 'required|in:available,sold,reserved'
        ]);

        $land->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Land status updated successfully.',
            'status' => $land->status
        ]);
    }
}