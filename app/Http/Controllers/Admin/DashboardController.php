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
        // Get statistics from all tables
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_fournisseurs' => User::where('role', 'fournisseur')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_lands' => TerreAgricole::count(),
            // Fix: Remove status check or use correct column name
            'available_lands' => TerreAgricole::count(), // Or use correct status column
            'total_annonces' => Annonce::count(),
            // Fix: Remove status check or use correct column name  
            'active_annonces' => Annonce::count(), // Or use correct status column
            'total_transactions' => Transaction::count(),
            // Fix: Remove status check or use correct column name
            'completed_transactions' => Transaction::count(), // Or use correct status column
            'total_revenue' => Transaction::sum('amount') ?: 0,
            'total_commission' => Transaction::sum('commission') ?: 0,
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        // Get recent users
        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        // Get recent transactions  
        $recentTransactions = Transaction::latest()
            ->limit(5)
            ->get();

        // Chart data for user growth (last 6 months)
        $userGrowthData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = User::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
            $userGrowthData[] = [
                'month' => $date->format('M'),
                'count' => $count
            ];
        }

        // Revenue data (last 6 months)
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Transaction::whereYear('created_at', $date->year)
                                 ->whereMonth('created_at', $date->month)
                                 ->sum('amount') ?: 0;
            $commission = Transaction::whereYear('created_at', $date->year)
                                   ->whereMonth('created_at', $date->month)
                                   ->sum('commission') ?: 0;
            $revenueData[] = [
                'month' => $date->format('M'),
                'revenue' => $revenue,
                'commission' => $commission
            ];
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentTransactions',
            'userGrowthData',
            'revenueData'
        ));
    }
}