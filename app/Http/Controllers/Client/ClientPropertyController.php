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
        $query = Annonce::with(['fournisseur.user'])
            ->where('is_active', true); // Only check is_active

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        // Filter by property type (adjust field name if needed)
        if ($request->filled('property_type')) {
            $query->where('type', $request->property_type);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', "%{$request->location}%");
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['created_at', 'price', 'title'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $properties = $query->paginate(12)->withQueryString();

        // Get saved property IDs for current user
        $savedPropertyIds = auth()->user()->savedProperties()->pluck('property_id')->toArray();

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

        $property->load(['fournisseur.user']);

        // Check if user has saved this property
        $isSaved = auth()->user()->savedProperties()
            ->where('property_id', $property->id)
            ->exists();

        // Get related properties
        $relatedProperties = Annonce::with(['fournisseur.user'])
            ->where('is_active', true)
            ->where('id', '!=', $property->id)
            ->limit(4)
            ->get();

        return view('client.properties.show', compact('property', 'isSaved', 'relatedProperties'));
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
     * Toggle save/unsave property.
     */
    public function toggleSave(Annonce $annonce)
    {
        $property = $annonce;
        
        $saved = auth()->user()->savedProperties()
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
     * Show saved properties.
     */
    public function savedProperties()
    {
        $savedProperties = auth()->user()->savedProperties()
            ->with(['annonce.fournisseur.user'])
            ->whereHas('annonce', function($query) {
                $query->where('is_active', true);
            })
            ->latest()
            ->paginate(12);

        return view('client.properties.saved', compact('savedProperties'));
    }
}