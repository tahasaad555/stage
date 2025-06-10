<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TerreAgricole;
use App\Models\Annonce;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index()
{
    try {
        // Basic statistics
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_fournisseurs' => User::where('role', 'fournisseur')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_lands' => TerreAgricole::count(),
            'available_lands' => TerreAgricole::count(), // Adjust if you have status column
            'total_annonces' => Annonce::count(),
            'active_annonces' => Annonce::count(), // Adjust if you have status column
            'total_revenue' => Transaction::sum('amount') ?: 0,
            'total_commission' => Transaction::sum('commission') ?: 0,
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'completed_transactions' => Transaction::count(), // Adjust if you have status column
        ];

        // User growth data for chart
        $userGrowthData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $userGrowthData[] = [
                'month' => $month->format('M'),
                'count' => User::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count()
            ];
        }

        // Revenue data for chart
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyRevenue = Transaction::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount') ?: 0;
            
            $monthlyCommission = Transaction::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('commission') ?: 0;

            $revenueData[] = [
                'month' => $month->format('M'),
                'revenue' => (float) $monthlyRevenue,
                'commission' => (float) $monthlyCommission
            ];
        }

        // Recent users
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'userGrowthData',
            'revenueData', 
            'recentUsers',
            'recentTransactions'
        ));

    } catch (\Exception $e) {
        // Log the error
        \Log::error('Dashboard error: ' . $e->getMessage());
        
        // Return view with default data
        $stats = [
            'total_users' => 0,
            'total_clients' => 0,
            'total_fournisseurs' => 0,
            'total_admins' => 0,
            'total_lands' => 0,
            'available_lands' => 0,
            'total_annonces' => 0,
            'active_annonces' => 0,
            'total_revenue' => 0,
            'total_commission' => 0,
            'new_users_this_month' => 0,
            'completed_transactions' => 0,
        ];

        $userGrowthData = [
            ['month' => 'Jan', 'count' => 0],
            ['month' => 'Feb', 'count' => 0],
            ['month' => 'Mar', 'count' => 0],
            ['month' => 'Apr', 'count' => 0],
            ['month' => 'May', 'count' => 0],
            ['month' => 'Jun', 'count' => 0],
        ];

        $revenueData = [
            ['month' => 'Jan', 'revenue' => 0, 'commission' => 0],
            ['month' => 'Feb', 'revenue' => 0, 'commission' => 0],
            ['month' => 'Mar', 'revenue' => 0, 'commission' => 0],
            ['month' => 'Apr', 'revenue' => 0, 'commission' => 0],
            ['month' => 'May', 'revenue' => 0, 'commission' => 0],
            ['month' => 'Jun', 'revenue' => 0, 'commission' => 0],
        ];

        $recentUsers = collect();
        $recentTransactions = collect();

        return view('admin.dashboard', compact(
            'stats',
            'userGrowthData',
            'revenueData',
            'recentUsers',
            'recentTransactions'
        ));
    }
}}