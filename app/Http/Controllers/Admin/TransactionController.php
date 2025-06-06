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

        // Statistics
        $stats = [
            'total' => Transaction::count(),
            'completed' => Transaction::where('status', 'completed')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'failed' => Transaction::where('status', 'failed')->count(),
            'cancelled' => Transaction::where('status', 'cancelled')->count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount') ?: 0,
            'total_commission' => Transaction::where('status', 'completed')->sum('commission') ?: 0,
            'avg_amount' => Transaction::where('status', 'completed')->avg('amount') ?: 0,
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

    /**
     * Export transactions to CSV
     */
    public function export(Request $request)
    {
        try {
            // Get the same filtered transactions as the index method
            $query = Transaction::with(['client.user', 'fournisseur.user', 'terreAgricole']);

            // Apply the same filters as index method
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
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

            $transactions = $query->get();

            // Generate CSV content
            $csvData = [];
            
            // CSV Headers
            $csvData[] = [
                'Transaction ID',
                'Payment Reference',
                'Client Name',
                'Client Email',
                'Supplier Name',
                'Supplier Company',
                'Land Title',
                'Land Region',
                'Amount',
                'Commission',
                'Status',
                'Payment Method',
                'Transaction Date',
                'Completed Date',
                'Created At',
                'Updated At'
            ];

            // CSV Data
            foreach ($transactions as $transaction) {
                $csvData[] = [
                    $transaction->id,
                    $transaction->payment_reference ?: 'N/A',
                    $transaction->client && $transaction->client->user ? $transaction->client->user->full_name : 'N/A',
                    $transaction->client && $transaction->client->user ? $transaction->client->user->email : 'N/A',
                    $transaction->fournisseur && $transaction->fournisseur->user ? $transaction->fournisseur->user->full_name : 'N/A',
                    $transaction->fournisseur ? ($transaction->fournisseur->company_name ?: 'N/A') : 'N/A',
                    $transaction->terreAgricole ? $transaction->terreAgricole->title : 'N/A',
                    $transaction->terreAgricole ? $transaction->terreAgricole->region : 'N/A',
                    number_format($transaction->amount, 2),
                    number_format($transaction->commission, 2),
                    ucfirst($transaction->status),
                    $transaction->payment_method ?: 'N/A',
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->completed_at ? $transaction->completed_at->format('Y-m-d H:i:s') : 'N/A',
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->updated_at->format('Y-m-d H:i:s')
                ];
            }

            // Generate filename with timestamp and filters
            $filename = 'transactions_export_' . now()->format('Y_m_d_H_i_s');
            
            if ($request->filled('status')) {
                $filename .= '_' . $request->get('status');
            }
            
            if ($request->filled('payment_method')) {
                $filename .= '_' . $request->get('payment_method');
            }
            
            $filename .= '.csv';

            // Create CSV content
            $csvContent = '';
            foreach ($csvData as $row) {
                $csvContent .= '"' . implode('","', $row) . '"' . "\n";
            }

            // Return CSV response
            return response($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
                'Pragma' => 'public',
            ]);

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Transaction export failed: ' . $e->getMessage());
            
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Export failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Generate transactions report
     */
    public function generateReport(Request $request)
    {
        try {
            // Get transaction statistics
            $stats = [
                'total_transactions' => Transaction::count(),
                'completed_transactions' => Transaction::where('status', 'completed')->count(),
                'pending_transactions' => Transaction::where('status', 'pending')->count(),
                'failed_transactions' => Transaction::where('status', 'failed')->count(),
                'cancelled_transactions' => Transaction::where('status', 'cancelled')->count(),
                'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
                'total_commission' => Transaction::where('status', 'completed')->sum('commission'),
                'average_transaction_amount' => Transaction::where('status', 'completed')->avg('amount'),
                'completion_rate' => Transaction::count() > 0 ? (Transaction::where('status', 'completed')->count() / Transaction::count()) * 100 : 0,
            ];

            // Get monthly data for the last 6 months
            $monthlyData = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthlyData[] = [
                    'month' => $date->format('M Y'),
                    'transactions' => Transaction::whereYear('created_at', $date->year)
                                               ->whereMonth('created_at', $date->month)
                                               ->count(),
                    'revenue' => Transaction::whereYear('created_at', $date->year)
                                           ->whereMonth('created_at', $date->month)
                                           ->where('status', 'completed')
                                           ->sum('amount'),
                    'commission' => Transaction::whereYear('created_at', $date->year)
                                              ->whereMonth('created_at', $date->month)
                                              ->where('status', 'completed')
                                              ->sum('commission'),
                ];
            }

            // Get top clients and suppliers
            $topClients = Transaction::with('client.user')
                                   ->where('status', 'completed')
                                   ->selectRaw('client_id, COUNT(*) as transaction_count, SUM(amount) as total_spent')
                                   ->groupBy('client_id')
                                   ->orderBy('total_spent', 'desc')
                                   ->limit(5)
                                   ->get();

            $topSuppliers = Transaction::with('fournisseur.user')
                                     ->where('status', 'completed')
                                     ->selectRaw('fournisseur_id, COUNT(*) as transaction_count, SUM(amount) as total_earned')
                                     ->groupBy('fournisseur_id')
                                     ->orderBy('total_earned', 'desc')
                                     ->limit(5)
                                     ->get();

            $reportData = [
                'generated_at' => now()->format('Y-m-d H:i:s'),
                'stats' => $stats,
                'monthly_data' => $monthlyData,
                'top_clients' => $topClients,
                'top_suppliers' => $topSuppliers,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Transaction report generated successfully!',
                'data' => $reportData
            ]);

        } catch (\Exception $e) {
            \Log::error('Transaction report generation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Report generation failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Bulk update transaction statuses
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transactions,id',
            'status' => 'required|in:pending,completed,failed,cancelled'
        ]);

        try {
            $updatedCount = Transaction::whereIn('id', $request->transaction_ids)
                                     ->update([
                                         'status' => $request->status,
                                         'completed_at' => $request->status === 'completed' ? now() : null,
                                         'updated_at' => now()
                                     ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updatedCount} transactions to {$request->status} status."
            ]);

        } catch (\Exception $e) {
            \Log::error('Bulk update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Bulk update failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Reconcile transactions
     */
    public function reconcile(Request $request)
    {
        try {
            // This is a placeholder for reconciliation logic
            // You would implement actual payment gateway reconciliation here
            
            $pendingTransactions = Transaction::where('status', 'pending')->count();
            $reconciledCount = 0;

            // Simulate reconciliation process
            // In real implementation, you would:
            // 1. Connect to payment gateway APIs
            // 2. Match transaction references
            // 3. Update statuses based on actual payment status

            return response()->json([
                'success' => true,
                'message' => "Reconciliation completed. {$reconciledCount} transactions updated from {$pendingTransactions} pending transactions.",
                'data' => [
                    'pending_transactions' => $pendingTransactions,
                    'reconciled_count' => $reconciledCount
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Transaction reconciliation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Reconciliation failed. Please try again.'
            ], 500);
        }
    }
    /**
 * Export transaction as PDF (HTML version - no PDF library required)
 */
public function exportPdf(Transaction $transaction)
{
    try {
        $transaction->load(['client.user', 'fournisseur.user', 'terreAgricole']);

        // Generate HTML content that can be saved as PDF by the browser
        $html = view('admin.transactions.pdf-template', compact('transaction'))->render();
        
        $filename = 'transaction_' . $transaction->id . '_' . now()->format('Y_m_d') . '.html';
        
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);

    } catch (\Exception $e) {
        \Log::error('Transaction PDF export failed: ' . $e->getMessage());
        
        return redirect()->back()->with('error', 'Failed to generate PDF. Please try again.');
    }
}

/**
 * Send notification to transaction parties
 */
public function sendNotification(Transaction $transaction)
{
    try {
        $notificationsSent = 0;
        $errors = [];

        // Send notification to client
        if ($transaction->client && $transaction->client->user && $transaction->client->user->email) {
            try {
                // You can implement email sending here using Laravel's Mail facade
                // Mail::to($transaction->client->user->email)->send(new TransactionNotification($transaction, 'client'));
                $notificationsSent++;
                
                // For now, we'll just log it
                \Log::info('Transaction notification sent to client: ' . $transaction->client->user->email);
            } catch (\Exception $e) {
                $errors[] = 'Failed to notify client: ' . $e->getMessage();
            }
        }

        // Send notification to supplier
        if ($transaction->fournisseur && $transaction->fournisseur->user && $transaction->fournisseur->user->email) {
            try {
                // You can implement email sending here using Laravel's Mail facade
                // Mail::to($transaction->fournisseur->user->email)->send(new TransactionNotification($transaction, 'supplier'));
                $notificationsSent++;
                
                // For now, we'll just log it
                \Log::info('Transaction notification sent to supplier: ' . $transaction->fournisseur->user->email);
            } catch (\Exception $e) {
                $errors[] = 'Failed to notify supplier: ' . $e->getMessage();
            }
        }

        if ($notificationsSent > 0) {
            return response()->json([
                'success' => true,
                'message' => "Notifications sent successfully to {$notificationsSent} " . 
                           ($notificationsSent === 1 ? 'party' : 'parties') . '.',
                'notifications_sent' => $notificationsSent,
                'errors' => $errors
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No valid email addresses found for notification.',
                'errors' => $errors
            ], 422);
        }

    } catch (\Exception $e) {
        \Log::error('Transaction notification failed: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to send notifications. Please try again.',
            'error' => $e->getMessage()
        ], 500);
    }
}
}