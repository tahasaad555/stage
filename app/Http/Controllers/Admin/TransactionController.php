<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['client.user', 'fournisseur.user', 'terreAgricole']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                  ->orWhereHas('client.user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('fournisseur.user', function($userQuery) use ($search) {
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

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Amount range filter
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        $transactions = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total' => Transaction::count(),
            'completed' => Transaction::where('status', 'completed')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'failed' => Transaction::where('status', 'failed')->count(),
            'cancelled' => Transaction::where('status', 'cancelled')->count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
            'total_commission' => Transaction::where('status', 'completed')->sum('commission'),
            'avg_amount' => Transaction::where('status', 'completed')->avg('amount'),
        ];

        // Get unique payment methods for filter
        $paymentMethods = Transaction::whereNotNull('payment_method')
                                   ->distinct()
                                   ->pluck('payment_method')
                                   ->filter();

        return view('admin.transactions.index', compact('transactions', 'stats', 'paymentMethods'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['client.user', 'fournisseur.user', 'terreAgricole']);
        
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled'
        ]);

        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        $transaction->update([
            'status' => $newStatus,
            'completed_at' => $newStatus === 'completed' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Transaction status updated from {$oldStatus} to {$newStatus}.",
            'status' => $transaction->status
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        // Only allow deletion of pending or failed transactions
        if (in_array($transaction->status, ['completed'])) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete completed transactions.'
                ], 422);
            }

            return redirect()->route('admin.transactions.index')
                ->with('error', 'Cannot delete completed transactions.');
        }

        $transaction->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaction deleted successfully.'
            ]);
        }

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}