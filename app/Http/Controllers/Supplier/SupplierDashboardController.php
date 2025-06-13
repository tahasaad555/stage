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
    /**
 * Show the analytics page
 */
public function analytics()
{
    $user = auth()->user();
    $fournisseur = $user->fournisseur;
    
    // Initialize default analytics data
    $analyticsData = [
        'views' => [
            'monthly' => $this->getMonthlyViewsData($fournisseur),
            'weekly' => $this->getWeeklyViewsData($fournisseur),
            'total' => $this->getMonthlyViews($fournisseur)
        ],
        'inquiries' => [
            'monthly' => $this->getMonthlyInquiriesData($fournisseur),
            'conversion_rate' => $this->getConversionRate($fournisseur),
            'total' => $this->getInquiriesCount($fournisseur)
        ],
        'properties' => [
            'performance' => $this->getPropertyPerformanceData($fournisseur),
            'by_type' => $this->getPropertiesByType($fournisseur),
            'active_vs_inactive' => $this->getActiveInactiveData($fournisseur)
        ],
        'revenue' => [
            'monthly' => $this->getMonthlyRevenueData($fournisseur),
            'by_property' => $this->getRevenueByProperty($fournisseur),
            'total' => $this->getTotalRevenue($fournisseur)
        ]
    ];
    
    return view('supplier.analytics', [
        'user' => $user,
        'fournisseur' => $fournisseur,
        'analytics' => $analyticsData
    ]);
}

/**
 * Get monthly views data for charts
 */
private function getMonthlyViewsData($fournisseur)
{
    // Placeholder data - replace with real analytics when implemented
    return [
        ['month' => 'Jan', 'views' => rand(800, 1200)],
        ['month' => 'Feb', 'views' => rand(900, 1300)],
        ['month' => 'Mar', 'views' => rand(1000, 1400)],
        ['month' => 'Apr', 'views' => rand(1100, 1500)],
        ['month' => 'May', 'views' => rand(1200, 1600)],
        ['month' => 'Jun', 'views' => rand(1300, 1700)]
    ];
}

/**
 * Get weekly views data
 */
private function getWeeklyViewsData($fournisseur)
{
    return [
        ['day' => 'Mon', 'views' => rand(50, 150)],
        ['day' => 'Tue', 'views' => rand(60, 160)],
        ['day' => 'Wed', 'views' => rand(70, 170)],
        ['day' => 'Thu', 'views' => rand(80, 180)],
        ['day' => 'Fri', 'views' => rand(90, 190)],
        ['day' => 'Sat', 'views' => rand(40, 140)],
        ['day' => 'Sun', 'views' => rand(30, 130)]
    ];
}

/**
 * Get monthly inquiries data
 */
private function getMonthlyInquiriesData($fournisseur)
{
    return [
        ['month' => 'Jan', 'inquiries' => rand(10, 25)],
        ['month' => 'Feb', 'inquiries' => rand(12, 28)],
        ['month' => 'Mar', 'inquiries' => rand(15, 30)],
        ['month' => 'Apr', 'inquiries' => rand(18, 35)],
        ['month' => 'May', 'inquiries' => rand(20, 40)],
        ['month' => 'Jun', 'inquiries' => rand(25, 45)]
    ];
}

/**
 * Get conversion rate data
 */
private function getConversionRate($fournisseur)
{
    return [
        'current_month' => rand(15, 35) / 10, // 1.5% to 3.5%
        'previous_month' => rand(10, 30) / 10,
        'average' => rand(20, 25) / 10
    ];
}

/**
 * Get property performance data
 */
private function getPropertyPerformanceData($fournisseur)
{
    if (!$fournisseur) return [];
    
    return $fournisseur->annonces()->take(5)->get()->map(function($property) {
        return [
            'name' => $property->titre ?? 'Property #' . $property->id,
            'views' => rand(50, 500),
            'inquiries' => rand(2, 15),
            'conversion_rate' => rand(10, 50) / 10
        ];
    })->toArray();
}

/**
 * Get properties by type data
 */
private function getPropertiesByType($fournisseur)
{
    return [
        ['type' => 'Vineyard', 'count' => rand(5, 15)],
        ['type' => 'Crop Land', 'count' => rand(8, 20)],
        ['type' => 'Orchard', 'count' => rand(3, 12)],
        ['type' => 'Pasture', 'count' => rand(4, 10)]
    ];
}

/**
 * Get active vs inactive properties
 */
private function getActiveInactiveData($fournisseur)
{
    if (!$fournisseur) {
        return ['active' => 0, 'inactive' => 0];
    }
    
    $total = $fournisseur->annonces()->count();
    $active = $fournisseur->annonces()->where('is_active', true)->count();
    
    return [
        'active' => $active,
        'inactive' => $total - $active
    ];
}

/**
 * Get monthly revenue data
 */
private function getMonthlyRevenueData($fournisseur)
{
    return [
        ['month' => 'Jan', 'revenue' => rand(5000, 15000)],
        ['month' => 'Feb', 'revenue' => rand(6000, 16000)],
        ['month' => 'Mar', 'revenue' => rand(7000, 17000)],
        ['month' => 'Apr', 'revenue' => rand(8000, 18000)],
        ['month' => 'May', 'revenue' => rand(9000, 19000)],
        ['month' => 'Jun', 'revenue' => rand(10000, 20000)]
    ];
}

/**
 * Get revenue by property
 */
private function getRevenueByProperty($fournisseur)
{
    if (!$fournisseur) return [];
    
    return $fournisseur->annonces()->take(5)->get()->map(function($property) {
        return [
            'name' => $property->titre ?? 'Property #' . $property->id,
            'revenue' => rand(1000, 5000)
        ];
    })->toArray();
}

/**
 * Get total revenue
 */
private function getTotalRevenue($fournisseur)
{
    return rand(50000, 150000);
}
/**
 * Get analytics data via API
 */
public function getAnalyticsData(Request $request)
{
    $user = auth()->user();
    $fournisseur = $user->fournisseur;
    $timeRange = $request->get('range', 30); // days
    
    return response()->json([
        'views' => $this->getViewsDataByRange($fournisseur, $timeRange),
        'inquiries' => $this->getInquiriesDataByRange($fournisseur, $timeRange),
        'revenue' => $this->getRevenueDataByRange($fournisseur, $timeRange),
        'properties' => $this->getPropertyPerformanceData($fournisseur)
    ]);
}

/**
 * Export analytics report
 */
public function exportReport(Request $request)
{
    $user = auth()->user();
    $fournisseur = $user->fournisseur;
    $format = $request->get('format', 'pdf');
    
    // Generate report based on format
    // This is where you'd implement PDF/Excel generation
    
    return response()->json(['success' => true, 'message' => 'Report generated successfully']);
}
}