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
            $this->transactions = Transaction::orderBy('dateTransaction', 'desc')->get();
        } else {
            $client = $user->client;
            $this->transactions = Transaction::where('clientId', $client->id)
                                     ->orderBy('dateTransaction', 'desc')
                                     ->get();
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
        $transaction->statut = 'Cancelled';
        $transaction->save();
        
        session()->flash('message', 'Transaction cancelled successfully.');
        $this->loadTransactions();
    }
}