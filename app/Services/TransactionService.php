<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Fournisseur;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionService
{
    /**
     * Get transaction analytics for supplier
     */
    public function getSupplierAnalytics(Fournisseur $supplier, int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'total_transactions' => $supplier->transactions()->count(),
            'completed_transactions' => $supplier->transactions()->where('status', 'completed')->count(),
            'pending_transactions' => $supplier->transactions()->where('status', 'pending')->count(),
            'failed_transactions' => $supplier->transactions()->where('status', 'failed')->count(),
            'total_revenue' => $supplier->transactions()->where('status', 'completed')->sum('amount'),
            'total_commission' => $supplier->transactions()->where('status', 'completed')->sum('commission'),
            'avg_transaction_amount' => $supplier->transactions()->where('status', 'completed')->avg('amount'),
            'recent_activity' => $this->getRecentActivity($supplier, $days),
            'monthly_revenue' => $this->getMonthlyRevenue($supplier),
            'payment_methods' => $this->getPaymentMethodStats($supplier)
        ];
    }

    /**
     * Get recent transaction activity
     */
    private function getRecentActivity(Fournisseur $supplier, int $days): array
    {
        return $supplier->transactions()
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    /**
     * Get monthly revenue data
     */
    private function getMonthlyRevenue(Fournisseur $supplier): array
    {
        return $supplier->transactions()
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as revenue, SUM(commission) as commission')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'period' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'revenue' => $item->revenue,
                    'commission' => $item->commission
                ];
            })
            ->toArray();
    }

    /**
     * Get payment method statistics
     */
    private function getPaymentMethodStats(Fournisseur $supplier): array
    {
        return $supplier->transactions()
            ->whereNotNull('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('payment_method')
            ->get()
            ->map(function ($item) {
                return [
                    'method' => $item->payment_method,
                    'count' => $item->count,
                    'total_amount' => $item->total_amount,
                    'percentage' => 0 // Calculate percentage in controller
                ];
            })
            ->toArray();
    }

    /**
     * Generate transaction report
     */
    public function generateReport(Fournisseur $supplier, array $filters = []): array
    {
        $query = $supplier->transactions()->with(['client.user', 'terreAgricole']);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        return [
            'transactions' => $transactions,
            'summary' => [
                'total_count' => $transactions->count(),
                'completed_count' => $transactions->where('status', 'completed')->count(),
                'total_revenue' => $transactions->where('status', 'completed')->sum('amount'),
                'total_commission' => $transactions->where('status', 'completed')->sum('commission'),
                'avg_amount' => $transactions->where('status', 'completed')->avg('amount'),
            ],
            'filters' => $filters
        ];
    }

    /**
     * Get transaction status summary
     */
    public function getStatusSummary(Fournisseur $supplier): array
    {
        return [
            'completed' => [
                'count' => $supplier->transactions()->where('status', 'completed')->count(),
                'revenue' => $supplier->transactions()->where('status', 'completed')->sum('amount'),
                'commission' => $supplier->transactions()->where('status', 'completed')->sum('commission')
            ],
            'pending' => [
                'count' => $supplier->transactions()->where('status', 'pending')->count(),
                'potential_revenue' => $supplier->transactions()->where('status', 'pending')->sum('amount'),
                'potential_commission' => $supplier->transactions()->where('status', 'pending')->sum('commission')
            ],
            'failed' => [
                'count' => $supplier->transactions()->where('status', 'failed')->count(),
                'lost_revenue' => $supplier->transactions()->where('status', 'failed')->sum('amount')
            ]
        ];
    }
}