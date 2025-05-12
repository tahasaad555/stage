<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;

class OrderManagement extends Component
{
    public $orderId;
    public $order;
    public $newStatus;
    
    public function mount($id = null)
    {
        if ($id) {
            $this->orderId = $id;
            $this->loadOrder();
        }
    }
    
    public function loadOrder()
    {
        $this->order = Transaction::with(['client', 'paiements'])->findOrFail($this->orderId);
        $this->newStatus = $this->order->statut;
    }
    
    public function render()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            $orders = Transaction::with(['client'])->latest()->paginate(10);
        } elseif ($user->hasRole('client')) {
            $orders = Transaction::where('clientId', $user->client->id)
                              ->with(['client'])
                              ->latest()
                              ->paginate(10);
        } elseif ($user->hasRole('fournisseur')) {
            $orders = Transaction::whereHas('materiel', function($query) use ($user) {
                              $query->where('fournisseurId', $user->fournisseur->id);
                          })
                          ->with(['client'])
                          ->latest()
                          ->paginate(10);
        } else {
            $orders = collect();
        }
        
        return view('livewire.order-management', [
            'orders' => $orders
        ]);
    }
    
    public function viewOrder($id)
    {
        $this->orderId = $id;
        $this->loadOrder();
    }
    
    public function updateOrderStatus()
    {
        $this->validate([
            'newStatus' => 'required|in:Pending,Processing,Shipped,Completed,Cancelled'
        ]);
        
        $this->order->statut = $this->newStatus;
        $this->order->save();
        
        session()->flash('message', 'Order status updated successfully.');
    }
    
    public function recordPayment($amount, $method)
    {
        $this->validate([
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string'
        ]);
        
        $payment = new Paiement();
        $payment->transactionId = $this->order->id;
        $payment->methode = $method;
        $payment->dateTime = now();
        $payment->total = $amount;
        $payment->verified = false;
        $payment->save();
        
        session()->flash('message', 'Payment recorded successfully.');
        
        $this->loadOrder();
    }
    
    public function verifyPayment($paymentId)
    {
        $payment = Paiement::findOrFail($paymentId);
        $payment->verified = true;
        $payment->save();
        
        session()->flash('message', 'Payment verified successfully.');
        
        $this->loadOrder();
    }
}