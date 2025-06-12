<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Fournisseur;
use App\Models\Inquiry;
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
            'new' => 0,
            'monthly_views' => 0,
        ];
        
        $inquiryStats = [
            'total' => 0,
            'new' => 0,
            'read' => 0,
            'responded' => 0,
            'closed' => 0,
            'this_month' => 0,
            'this_week' => 0,
            'high_priority' => 0,
            'urgent' => 0,
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
                'new' => $this->getNewInquiriesCount($fournisseur),
                'monthly_views' => $this->getMonthlyViews($fournisseur),
            ];
            
            // Get detailed inquiry statistics
            $inquiryStats = $this->getInquiryStats($fournisseur);
        }
        
        // Get recent notifications (placeholder for now)
        $notifications = $this->getRecentNotifications($user);
        
        $data = [
            'user' => $user,
            'properties' => $properties,
            'inquiries' => collect(),
            'notifications' => $notifications,
            'stats' => $stats,
            'inquiryStats' => $inquiryStats,
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
     * Get inquiry statistics for supplier
     */
    private function getInquiryStats(Fournisseur $fournisseur)
    {
        $baseQuery = Inquiry::where('supplier_user_id', $fournisseur->user_id);
        $allInquiries = $baseQuery->get();
        
        return [
            'total' => $allInquiries->count(),
            'new' => $allInquiries->where('status', 'new')->count(),
            'read' => $allInquiries->where('status', 'read')->count(),
            'responded' => $allInquiries->where('status', 'responded')->count(),
            'closed' => $allInquiries->where('status', 'closed')->count(),
            'this_month' => $allInquiries->where('created_at', '>=', now()->startOfMonth())->count(),
            'this_week' => $allInquiries->where('created_at', '>=', now()->startOfWeek())->count(),
            'high_priority' => $allInquiries->where('priority', 'high')->count(),
            'urgent' => $allInquiries->where('priority', 'urgent')->count(),
        ];
    }

    /**
     * Get inquiries count for supplier (for backward compatibility)
     */
    private function getInquiriesCount(Fournisseur $fournisseur)
    {
        return Inquiry::where('supplier_user_id', $fournisseur->user_id)
            ->where('created_at', '>=', now()->subMonth())
            ->count();
    }

    /**
     * Get new inquiries count for supplier
     */
    private function getNewInquiriesCount(Fournisseur $fournisseur)
    {
        return Inquiry::where('supplier_user_id', $fournisseur->user_id)
            ->where('status', 'new')
            ->count();
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
        // Get real inquiry-based notifications
        $inquiryNotifications = collect();
        
        if ($user->fournisseur) {
            // Get recent new inquiries for notifications
            $recentInquiries = Inquiry::where('supplier_user_id', $user->id)
                ->where('status', 'new')
                ->latest()
                ->limit(3)
                ->get();
            
            foreach ($recentInquiries as $inquiry) {
                $inquiryNotifications->push((object) [
                    'id' => $inquiry->id,
                    'message' => "New inquiry received for: {$inquiry->subject}",
                    'type' => 'inquiry',
                    'created_at' => $inquiry->created_at,
                    'read' => false,
                    'url' => route('supplier.inquiries.show', $inquiry)
                ]);
            }
        }
        
        // Add some placeholder notifications if no real ones exist
        if ($inquiryNotifications->isEmpty()) {
            $sampleNotifications = collect([
                (object) [
                    'id' => 1,
                    'message' => 'Welcome to your supplier dashboard!',
                    'type' => 'system',
                    'created_at' => now()->subHours(1),
                    'read' => false,
                    'url' => '#'
                ],
                (object) [
                    'id' => 2,
                    'message' => 'Your profile is 85% complete. Add more details to attract clients.',
                    'type' => 'profile',
                    'created_at' => now()->subDay(),
                    'read' => false,
                    'url' => route('supplier.profile')
                ],
                (object) [
                    'id' => 3,
                    'message' => 'Tip: Add high-quality photos to your property listings for better engagement.',
                    'type' => 'tip',
                    'created_at' => now()->subDays(2),
                    'read' => true,
                    'url' => route('supplier.properties.index')
                ]
            ]);
            
            return $sampleNotifications->take(3);
        }

        return $inquiryNotifications->take(3); // Show only 3 most recent
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

        // Get real inquiry statistics
        $inquiryStats = $this->getInquiryStats($fournisseur);

        return response()->json([
            'stats' => [
                'total_properties' => $fournisseur->annonces()->count(),
                'active_properties' => $fournisseur->annonces()->where('is_active', true)->count(),
                'featured_properties' => $fournisseur->annonces()->where('is_featured', true)->count(),
                'total_value' => $totalValue,
                'total_surface' => $totalSurface,
                'avg_price_per_hectare' => $totalSurface > 0 ? round($totalValue / $totalSurface, 2) : 0,
                'monthly_views' => $this->getMonthlyViews($fournisseur),
                'total_inquiries' => $inquiryStats['total'],
                'new_inquiries' => $inquiryStats['new'],
                'responded_inquiries' => $inquiryStats['responded'],
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
                }),
            'recent_inquiries' => Inquiry::where('supplier_user_id', $user->id)
                ->with(['annonce'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function($inquiry) {
                    return [
                        'id' => $inquiry->id,
                        'subject' => $inquiry->subject,
                        'client_name' => $inquiry->client_name,
                        'status' => $inquiry->status,
                        'priority' => $inquiry->priority,
                        'property_title' => $inquiry->annonce->title ?? $inquiry->annonce->titre,
                        'created_at' => $inquiry->created_at->format('M d, Y'),
                        'time_since' => $inquiry->created_at->diffForHumans(),
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

        $inquiryStats = $this->getInquiryStats($fournisseur);
        
        // Calculate response rate
        $totalInquiries = $inquiryStats['total'];
        $respondedInquiries = $inquiryStats['responded'];
        $responseRate = $totalInquiries > 0 ? round(($respondedInquiries / $totalInquiries) * 100, 1) : 0;

        return response()->json([
            'properties' => $fournisseur->annonces()->count(),
            'inquiries' => $inquiryStats['new'], // Show new inquiries count
            'response_rate' => $responseRate
        ]);
    }
}