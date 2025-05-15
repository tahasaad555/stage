<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Annonce;
use App\Models\Transaction;
use App\Services\NotificationService;

class PendingAnnouncements extends Component
{
    use WithPagination;
    
    public $search = '';
    public $type = '';
    public $dateRange = '';
    public $startDate;
    public $endDate;
    
    public function mount()
    {
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }
    
    public function render()
    {
        $query = Annonce::where('estActif', false);
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('titre', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', function($q2) {
                      $q2->where('nom', 'like', '%' . $this->search . '%')
                         ->orWhere('prenom', 'like', '%' . $this->search . '%');
                  });
            });
        }
        
        if ($this->type) {
            $query->where('type', $this->type);
        }
        
        if ($this->dateRange === 'custom' && $this->startDate && $this->endDate) {
            $query->whereBetween('dateCreation', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
        } elseif ($this->dateRange === 'today') {
            $query->whereDate('dateCreation', now());
        } elseif ($this->dateRange === 'week') {
            $query->where('dateCreation', '>=', now()->subWeek());
        } elseif ($this->dateRange === 'month') {
            $query->where('dateCreation', '>=', now()->subMonth());
        }
        
        $pendingAnnouncements = $query->with('client')
                                     ->latest('dateCreation')
                                     ->paginate(10);
        
        return view('livewire.admin.pending-announcements', [
            'pendingAnnouncements' => $pendingAnnouncements
        ]);
    }
    
    public function approve($id)
    {
        $announcement = Annonce::with('client')->findOrFail($id);
        $announcement->estActif = true;
        $announcement->save();
        
        // Send notification to the client
        if ($announcement->client && $announcement->client->user) {
            NotificationService::sendSystemNotification(
                $announcement->client->user->id,
                "Your announcement '{$announcement->titre}' has been approved and is now active."
            );
        }
        
        session()->flash('message', 'Announcement approved successfully.');
    }
    
    public function reject($id)
    {
        $announcement = Annonce::with('client')->findOrFail($id);
        // We keep estActif as false, but we might want to add a reason for rejection
        
        // Send notification to the client
        if ($announcement->client && $announcement->client->user) {
            NotificationService::sendSystemNotification(
                $announcement->client->user->id,
                "Your announcement '{$announcement->titre}' has been rejected. Please review the guidelines and submit again."
            );
        }
        
        session()->flash('message', 'Announcement rejected successfully.');
    }
    
    public function delete($id)
    {
        $announcement = Annonce::findOrFail($id);
        $announcement->delete();
        
        session()->flash('message', 'Announcement deleted successfully.');
    }
    
    public function resetFilters()
    {
        $this->reset(['search', 'type', 'dateRange']);
        $this->resetPage();
    }
    
    public function applyCustomDateFilter()
    {
        $this->dateRange = 'custom';
        $this->resetPage();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingType()
    {
        $this->resetPage();
    }
    
    public function updatingDateRange()
    {
        $this->resetPage();
    }
}