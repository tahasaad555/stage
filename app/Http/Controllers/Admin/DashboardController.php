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
            'total_clients' => User::byRole('client')->count(),
            'total_fournisseurs' => User::byRole('fournisseur')->count(),
            'total_admins' => User::byRole('admin')->count(),
            'total_lands' => TerreAgricole::count(),
            'available_lands' => TerreAgricole::available()->count(),
            'total_annonces' => Annonce::count(),
            'active_annonces' => Annonce::active()->count(),
            'total_transactions' => Transaction::count(),
            'completed_transactions' => Transaction::completed()->count(),
            'total_revenue' => Transaction::completed()->sum('amount'),
            'total_commission' => Transaction::completed()->sum('commission'),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        // Get recent users
        $recentUsers = User::with(['client', 'fournisseur'])
            ->latest()
            ->limit(5)
            ->get();

        // Get recent transactions
        $recentTransactions = Transaction::with(['client.user', 'fournisseur.user', 'terreAgricole'])
            ->latest()
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
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        // Revenue data (last 6 months)
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Transaction::completed()
                                 ->whereYear('created_at', $date->year)
                                 ->whereMonth('created_at', $date->month)
                                 ->sum('amount');
            $revenueData[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
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