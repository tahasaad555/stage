<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class SupplierDashboardController extends Controller
{
    /**
     * Show the supplier dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $fournisseur = $user->fournisseur;
        
        // Initialize default data
        $properties = collect();
        $stats = [
            'total_properties' => 0,
            'active_properties' => 0,
            'total_inquiries' => 0,
            'monthly_views' => 0,
        ];
        
        // If user has supplier profile, get real data
        if ($fournisseur) {
            // Get recent properties for dashboard display
            $properties = $fournisseur->annonces()
                ->with(['terreAgricole'])
                ->latest()
                ->limit(3)
                ->get();
            
            // Calculate real statistics
            $allProperties = $fournisseur->annonces();
            
            $stats = [
                'total_properties' => $allProperties->count(),
                'active_properties' => $allProperties->where('is_active', true)->count(),
                'total_inquiries' => $this->getInquiriesCount($fournisseur),
                'monthly_views' => $this->getMonthlyViews($fournisseur),
            ];
        }
        
        // Get recent notifications (placeholder for now)
        $notifications = $this->getRecentNotifications($user);
        
        $data = [
            'user' => $user,
            'properties' => $properties,
            'inquiries' => collect(), // Will be populated when inquiry system is built
            'notifications' => $notifications,
            'stats' => $stats
        ];

        return view('supplier.dashboard', $data);
    }

    /**
     * Show the supplier profile.
     */
    public function profile()
    {
        return view('supplier.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the supplier profile.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();
        $user->update($request->only(['first_name', 'last_name', 'email', 'phone']));

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Get inquiries count for supplier
     * TODO: Implement when inquiry system is built
     */
    private function getInquiriesCount(Fournisseur $fournisseur)
    {
        // Placeholder - replace with real inquiry count when inquiry model exists
        // return $fournisseur->inquiries()->where('created_at', '>=', now()->subMonth())->count();
        return rand(5, 25); // Temporary random number for demo
    }

    /**
     * Get monthly views for supplier's properties
     * TODO: Implement when analytics system is built
     */
    private function getMonthlyViews(Fournisseur $fournisseur)
    {
        // Placeholder - replace with real view analytics when tracking is implemented
        // return $fournisseur->annonces()->sum('view_count_monthly');
        return rand(100, 1000); // Temporary random number for demo
    }

    /**
     * Get recent notifications for user
     * TODO: Implement when notification system is built
     */
    private function getRecentNotifications($user)
    {
        // Placeholder for notifications - replace with real notification system
        $sampleNotifications = collect([
            (object) [
                'id' => 1,
                'message' => 'New inquiry received for your property in Bordeaux',
                'type' => 'inquiry',
                'created_at' => now()->subHours(2),
                'read' => false
            ],
            (object) [
                'id' => 2,
                'message' => 'Your property listing "Premium Vineyard Land" was featured',
                'type' => 'featured',
                'created_at' => now()->subDay(),
                'read' => false
            ],
            (object) [
                'id' => 3,
                'message' => 'Monthly analytics report is now available',
                'type' => 'analytics',
                'created_at' => now()->subDays(3),
                'read' => true
            ]
        ]);

        return $sampleNotifications->take(3); // Show only 3 most recent
    }

    /**
     * Get dashboard summary data
     */
    public function getDashboardSummary()
    {
        $user = auth()->user();
        $fournisseur = $user->fournisseur;
        
        if (!$fournisseur) {
            return response()->json([
                'error' => 'Supplier profile not found'
            ], 404);
        }

        // Calculate portfolio value
        $totalValue = $fournisseur->annonces()
            ->join('terres_agricoles', 'annonces.terre_agricole_id', '=', 'terres_agricoles.id')
            ->sum('terres_agricoles.price');

        // Calculate total surface
        $totalSurface = $fournisseur->annonces()
            ->join('terres_agricoles', 'annonces.terre_agricole_id', '=', 'terres_agricoles.id')
            ->sum('terres_agricoles.surface');

        return response()->json([
            'stats' => [
                'total_properties' => $fournisseur->annonces()->count(),
                'active_properties' => $fournisseur->annonces()->where('is_active', true)->count(),
                'featured_properties' => $fournisseur->annonces()->where('is_featured', true)->count(),
                'total_value' => $totalValue,
                'total_surface' => $totalSurface,
                'avg_price_per_hectare' => $totalSurface > 0 ? round($totalValue / $totalSurface, 2) : 0,
                'monthly_views' => $this->getMonthlyViews($fournisseur),
                'total_inquiries' => $this->getInquiriesCount($fournisseur),
            ],
            'recent_properties' => $fournisseur->annonces()
                ->with(['terreAgricole'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function($property) {
                    return [
                        'id' => $property->id,
                        'title' => $property->title,
                        'status' => $property->is_active ? 'active' : 'inactive',
                        'featured' => $property->is_featured,
                        'price' => $property->terreAgricole ? $property->terreAgricole->price : 0,
                        'surface' => $property->terreAgricole ? $property->terreAgricole->surface : 0,
                        'region' => $property->terreAgricole ? $property->terreAgricole->region : '',
                        'created_at' => $property->created_at->format('M d, Y'),
                        'updated_at' => $property->updated_at->diffForHumans(),
                    ];
                })
        ]);
    }

    /**
     * Get quick stats for header
     */
    public function getQuickStats()
    {
        $user = auth()->user();
        $fournisseur = $user->fournisseur;
        
        if (!$fournisseur) {
            return response()->json([
                'properties' => 0,
                'inquiries' => 0,
                'response_rate' => 0
            ]);
        }

        return response()->json([
            'properties' => $fournisseur->annonces()->count(),
            'inquiries' => $this->getInquiriesCount($fournisseur),
            'response_rate' => rand(85, 98) // TODO: Calculate real response rate
        ]);
    }
}