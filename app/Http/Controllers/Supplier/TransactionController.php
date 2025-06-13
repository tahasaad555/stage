<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the supplier's transactions.
     */
    public function index(Request $request)
    {
        $supplier = Auth::user()->fournisseur;
        
        if (!$supplier) {
            return redirect()->route('supplier.dashboard')->with('error', 'Supplier profile not found.');
        }

        // Base query for supplier's transactions
        $query = Transaction::with(['client.user', 'terreAgricole'])
                           ->where('fournisseur_id', $supplier->id);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('client.user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('terreAgricole', function($landQuery) use ($search) {
                      $landQuery->where('title', 'like', "%{$search}%")
                               ->orWhere('region', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->get('payment_method'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'amount_high':
                $query->orderBy('amount', 'desc');
                break;
            case 'amount_low':
                $query->orderBy('amount', 'asc');
                break;
            case 'status':
                $query->orderBy('status', 'asc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }

        $transactions = $query->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => $supplier->transactions()->count(),
            'completed' => $supplier->transactions()->where('status', 'completed')->count(),
            'pending' => $supplier->transactions()->where('status', 'pending')->count(),
            'failed' => $supplier->transactions()->where('status', 'failed')->count(),
            'cancelled' => $supplier->transactions()->where('status', 'cancelled')->count(),
            'total_revenue' => $supplier->transactions()->where('status', 'completed')->sum('amount') ?: 0,
            'total_commission_earned' => $supplier->transactions()->where('status', 'completed')->sum('commission') ?: 0,
            'avg_amount' => $supplier->transactions()->where('status', 'completed')->avg('amount') ?: 0,
        ];

        // Get unique payment methods for filter
        $paymentMethods = $supplier->transactions()
                                 ->whereNotNull('payment_method')
                                 ->distinct()
                                 ->pluck('payment_method')
                                 ->filter();

        return view('supplier.transactions.index', compact('transactions', 'stats', 'paymentMethods'));
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        $supplier = Auth::user()->fournisseur;
        
        // Ensure this transaction belongs to the current supplier
        if ($transaction->fournisseur_id !== $supplier->id) {
            abort(403, 'Unauthorized access to this transaction.');
        }

        $transaction->load(['client.user', 'fournisseur.user', 'terreAgricole']);
        
        return view('supplier.transactions.show', compact('transaction'));
    }

    /**
     * Export transactions to CSV
     */
    public function export(Request $request)
    {
        $supplier = Auth::user()->fournisseur;
        
        if (!$supplier) {
            return redirect()->route('supplier.dashboard')->with('error', 'Supplier profile not found.');
        }

        // Get filtered transactions
        $query = Transaction::with(['client.user', 'terreAgricole'])
                           ->where('fournisseur_id', $supplier->id);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('client.user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->get('payment_method'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $csvContent = "Transaction ID,Payment Reference,Client Name,Client Email,Land Title,Land Region,Amount,Commission,Status,Payment Method,Transaction Date,Completed Date\n";

        foreach ($transactions as $transaction) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $transaction->id,
                $transaction->payment_reference ?: 'N/A',
                $transaction->client && $transaction->client->user ? $transaction->client->user->first_name . ' ' . $transaction->client->user->last_name : 'N/A',
                $transaction->client && $transaction->client->user ? $transaction->client->user->email : 'N/A',
                $transaction->terreAgricole ? $transaction->terreAgricole->title : 'N/A',
                $transaction->terreAgricole ? $transaction->terreAgricole->region : 'N/A',
                number_format($transaction->amount, 2),
                number_format($transaction->commission, 2),
                ucfirst($transaction->status),
                $transaction->payment_method ?: 'N/A',
                $transaction->created_at->format('Y-m-d H:i:s'),
                $transaction->completed_at ? $transaction->completed_at->format('Y-m-d H:i:s') : 'N/A'
            );
        }

        $filename = 'supplier_transactions_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get transaction analytics data
     */
    public function getAnalyticsData(Request $request)
    {
        $supplier = Auth::user()->fournisseur;
        
        if (!$supplier) {
            return response()->json(['error' => 'Supplier profile not found'], 404);
        }

        $timeRange = $request->get('range', 30); // Default to 30 days

        // Revenue over time
        $revenueData = $supplier->transactions()
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays($timeRange))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Status distribution
        $statusData = $supplier->transactions()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Payment method distribution
        $paymentMethodData = $supplier->transactions()
            ->whereNotNull('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        return response()->json([
            'revenue' => $revenueData,
            'status' => $statusData,
            'payment_methods' => $paymentMethodData,
            'period' => $timeRange . ' days'
        ]);
    }
}