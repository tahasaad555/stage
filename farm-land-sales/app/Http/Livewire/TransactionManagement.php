<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;

class TransactionManagement extends Component
{
    public $transactions;
    public $selectedTransaction;
    
    public function mount()
    {
        $this->loadTransactions();
    }
    
    public function loadTransactions()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            // Admin can see all transactions
            $this->transactions = Transaction::orderBy('dateTransaction', 'desc')->get();
        } else {
            // Clients and suppliers see only their own transactions
            $client = $user->client;
            if ($client) {
                $this->transactions = Transaction::where('clientId', $client->id)
                                     ->orderBy('dateTransaction', 'desc')
                                     ->get();
            } else if ($user->fournisseur) {
                // For suppliers, get transactions related to their equipment
                $this->transactions = Transaction::whereHas('materiel', function($query) use ($user) {
                                        $query->where('fournisseurId', $user->fournisseur->id);
                                    })
                                    ->orderBy('dateTransaction', 'desc')
                                    ->get();
            } else {
                $this->transactions = collect(); // Empty collection if neither client nor supplier
            }
        }
    }
    
    public function render()
    {
        return view('livewire.transaction-management');
    }
    
    public function viewTransaction($id)
    {
        $this->selectedTransaction = Transaction::with('paiements')->findOrFail($id);
    }
    
    public function cancelTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Check if the user has permission to cancel this transaction
        $user = Auth::user();
        if (!$user->hasRole('admin') && $user->client && $transaction->clientId != $user->client->id) {
            session()->flash('error', 'You do not have permission to cancel this transaction.');
            return;
        }
        
        // Only allow cancellation if transaction is not already completed or cancelled
        if (in_array($transaction->statut, ['Completed', 'Cancelled'])) {
            session()->flash('error', 'Cannot cancel a transaction that is already completed or cancelled.');
            return;
        }
        
        $transaction->statut = 'Cancelled';
        $transaction->save();
        
        session()->flash('message', 'Transaction cancelled successfully.');
        $this->loadTransactions();
    }
    
    // Add the __invoke method for route action
    public function __invoke()
    {
        return $this->render();
    }
}