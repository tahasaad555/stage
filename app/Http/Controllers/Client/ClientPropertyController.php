<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\Inquiry;
use App\Models\SavedProperty;

class ClientPropertyController extends Controller
{
    /**
     * Display a listing of available properties.
     */
    public function index(Request $request)
    {
        $query = Annonce::with(['fournisseur.user', 'terreAgricole'])
            ->where('is_active', true)
            ->whereHas('terreAgricole'); // Ensure related land data exists

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                  ->orWhere('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('terreAgricole', function($tq) use ($search) {
                      $tq->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('region', 'LIKE', "%{$search}%")
                        ->orWhere('localisation', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by property type based on soil_type
        if ($request->filled('property_type')) {
            $query->whereHas('terreAgricole', function($q) use ($request) {
                $q->where('soil_type', 'LIKE', "%{$request->property_type}%");
            });
        }

        // Filter by price range - check both annonce price and terre_agricole price
        if ($request->filled('min_price')) {
            $query->where(function($q) use ($request) {
                $q->where('prix', '>=', $request->min_price)
                  ->orWhereHas('terreAgricole', function($tq) use ($request) {
                      $tq->where('price', '>=', $request->min_price);
                  });
            });
        }
        if ($request->filled('max_price')) {
            $query->where(function($q) use ($request) {
                $q->where('prix', '<=', $request->max_price)
                  ->orWhereHas('terreAgricole', function($tq) use ($request) {
                      $tq->where('price', '<=', $request->max_price);
                  });
            });
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->whereHas('terreAgricole', function($q) use ($request) {
                $q->where('region', 'LIKE', "%{$request->location}%")
                  ->orWhere('localisation', 'LIKE', "%{$request->location}%");
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['created_at', 'prix', 'titre'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } elseif ($sortBy === 'price') {
            $query->orderBy('prix', $sortOrder);
        }

        $properties = $query->paginate(12)->withQueryString();

        // Get saved property IDs for current user - FIXED
        $savedPropertyIds = [];
        if (auth()->check()) {
            $savedPropertyIds = auth()->user()->savedProperties()
                ->pluck('property_id')
                ->toArray();
        }

        return view('client.properties.index', compact('properties', 'savedPropertyIds'));
    }

    /**
     * Display the specified property.
     */
    public function show(Annonce $annonce)
    {
        $property = $annonce;
        
        // Check if property is available for viewing
        if (!$property->is_active) {
            abort(404);
        }

        $property->load(['fournisseur.user', 'terreAgricole']);

        // Check if user has saved this property - FIXED
        $isSaved = false;
        if (auth()->check()) {
            $isSaved = auth()->user()->savedProperties()
                ->where('property_id', $property->id)
                ->exists();
        }

        // Get related properties
        $relatedProperties = Annonce::with(['fournisseur.user', 'terreAgricole'])
            ->where('is_active', true)
            ->where('id', '!=', $property->id)
            ->whereHas('terreAgricole')
            ->limit(4)
            ->get();

        return view('client.properties.show', compact('property', 'isSaved', 'relatedProperties'));
    }

    /**
     * Map view of properties
     */
    public function mapView(Request $request)
    {
        $query = Annonce::with(['fournisseur.user', 'terreAgricole'])
            ->where('is_active', true)
            ->whereHas('terreAgricole', function($q) {
                $q->whereNotNull('gps_coordinates');
            });

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                  ->orWhere('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('terreAgricole', function($tq) use ($search) {
                      $tq->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('region', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('property_type')) {
            $query->whereHas('terreAgricole', function($q) use ($request) {
                $q->where('soil_type', 'LIKE', "%{$request->property_type}%");
            });
        }

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->where(function($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('prix', '>=', $request->min_price)
                      ->orWhereHas('terreAgricole', function($tq) use ($request) {
                          $tq->where('price', '>=', $request->min_price);
                      });
                }
                if ($request->filled('max_price')) {
                    $q->where('prix', '<=', $request->max_price)
                      ->orWhereHas('terreAgricole', function($tq) use ($request) {
                          $tq->where('price', '<=', $request->max_price);
                      });
                }
            });
        }

        $properties = $query->get();

        // Get saved property IDs for current user
        $savedPropertyIds = [];
        if (auth()->check()) {
            $savedPropertyIds = auth()->user()->savedProperties()
                ->pluck('property_id')
                ->toArray();
        }

        return view('client.properties.map', compact('properties', 'savedPropertyIds'));
    }

    /**
     * Send inquiry about a property.
     */
    public function sendInquiry(Request $request, Annonce $annonce)
    {
        $property = $annonce;
        
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'inquiry_type' => 'required|in:information,purchase,partnership,visit',
            'budget_range' => 'nullable|string|max:100',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        // Create inquiry
        Inquiry::create([
            'subject' => $request->subject,
            'message' => $request->message,
            'client_name' => auth()->user()->full_name,
            'client_email' => auth()->user()->email,
            'client_phone' => auth()->user()->phone,
            'inquiry_type' => $request->inquiry_type,
            'budget_range' => $request->budget_range,
            'priority' => $request->priority,
            'status' => 'new',
            'property_id' => $property->id,
            'client_user_id' => auth()->id(),
            'supplier_user_id' => $property->fournisseur->user_id,
        ]);

        return back()->with('success', 'Your inquiry has been sent successfully! The property owner will contact you soon.');
    }

    /**
     * Toggle save/unsave property - FIXED
     */
    public function toggleSave(Annonce $annonce)
    {
        $property = $annonce;
        
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to save properties.',
                'action' => 'login_required'
            ]);
        }

        $saved = SavedProperty::where('user_id', auth()->id())
            ->where('property_id', $property->id)
            ->first();

        if ($saved) {
            $saved->delete();
            $message = 'Property removed from saved list.';
            $action = 'removed';
        } else {
            SavedProperty::create([
                'user_id' => auth()->id(),
                'property_id' => $property->id,
            ]);
            $message = 'Property saved successfully!';
            $action = 'saved';
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'action' => $action
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Show saved properties - FIXED
     */
    public function savedProperties()
    {
        $savedProperties = SavedProperty::where('user_id', auth()->id())
            ->with(['property.fournisseur.user', 'property.terreAgricole'])
            ->whereHas('property', function($query) {
                $query->where('is_active', true)
                      ->whereHas('terreAgricole');
            })
            ->latest()
            ->paginate(12);

        return view('client.properties.saved', compact('savedProperties'));
    }

    /**
     * Report a property
     */
    public function reportProperty(Request $request, Annonce $annonce)
    {
        $request->validate([
            'reason' => 'required|string|in:inappropriate_content,misleading_info,duplicate_listing,property_sold,spam,other',
            'details' => 'nullable|string|max:1000',
        ]);

        \Log::info('Property Report', [
            'property_id' => $annonce->id,
            'reporter_id' => auth()->id(),
            'reason' => $request->reason,
            'details' => $request->details,
            'reported_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your report. We will review it shortly.'
        ]);
    }

    /**
     * Bulk save properties
     */
    public function bulkSave(Request $request)
    {
        $request->validate([
            'property_ids' => 'required|array',
            'property_ids.*' => 'exists:annonces,id'
        ]);

        $userId = auth()->id();
        $saved = 0;

        foreach ($request->property_ids as $propertyId) {
            $exists = SavedProperty::where('user_id', $userId)
                ->where('property_id', $propertyId)
                ->exists();

            if (!$exists) {
                SavedProperty::create([
                    'user_id' => $userId,
                    'property_id' => $propertyId,
                ]);
                $saved++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully saved {$saved} properties.",
            'saved_count' => $saved
        ]);
    }

    /**
     * Bulk remove saved properties
     */
    public function bulkRemove(Request $request)
    {
        $request->validate([
            'property_ids' => 'required|array',
            'property_ids.*' => 'exists:annonces,id'
        ]);

        $removed = SavedProperty::where('user_id', auth()->id())
            ->whereIn('property_id', $request->property_ids)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "Successfully removed {$removed} properties.",
            'removed_count' => $removed
        ]);
    }

    /**
     * Export saved properties
     */
    public function exportSaved()
    {
        $savedProperties = SavedProperty::where('user_id', auth()->id())
            ->with(['property.fournisseur.user', 'property.terreAgricole'])
            ->whereHas('property', function($query) {
                $query->where('is_active', true);
            })
            ->get();

        $csvData = [];
        $csvData[] = ['Title', 'Price (MAD)', 'Location', 'Area (Hectares)', 'Supplier', 'Saved Date'];

        foreach ($savedProperties as $saved) {
            $property = $saved->property;
            $price = $property->prix ?: ($property->terreAgricole->price ?? 0);
            $location = $property->terreAgricole->region ?? 'N/A';
            $area = $property->terreAgricole->surface ?? 'N/A';
            
            $csvData[] = [
                $property->titre ?: $property->title,
                number_format($price) . ' MAD',
                $location,
                $area ? $area . ' hectares' : 'N/A',
                $property->fournisseur->company_name ?? $property->fournisseur->user->full_name ?? 'N/A',
                $saved->created_at->format('Y-m-d')
            ];
        }

        $filename = 'saved_properties_' . now()->format('Y-m-d') . '.csv';
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}