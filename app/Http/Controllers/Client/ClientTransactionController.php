<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class ClientTransactionController extends Controller
{
    /**
     * Display a listing of client's transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['property', 'supplier'])
            ->where('client_id', auth()->user()->client->id ?? null)
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $transactions = $query->paginate(10)->withQueryString();

        // Get statistics
        $clientId = auth()->user()->client->id ?? null;
        $stats = [
            'total' => Transaction::where('client_id', $clientId)->count(),
            'pending' => Transaction::where('client_id', $clientId)->where('status', 'pending')->count(),
            'completed' => Transaction::where('client_id', $clientId)->where('status', 'completed')->count(),
            'cancelled' => Transaction::where('client_id', $clientId)->where('status', 'cancelled')->count(),
            'total_amount' => Transaction::where('client_id', $clientId)->where('status', 'completed')->sum('amount'),
        ];

        return view('client.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        // Check if the transaction belongs to the authenticated client
        if ($transaction->client_id !== (auth()->user()->client->id ?? null)) {
            abort(403);
        }

        $transaction->load(['property.user.fournisseur', 'supplier', 'documents']);

        return view('client.transactions.show', compact('transaction'));
    }
}