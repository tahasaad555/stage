<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\MaterielFermierAgricole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EquipmentManagement extends Component
{
    use WithPagination, WithFileUploads;
    
    public $typeEquipment;
    public $description;
    public $prix;
    public $estNeuf = true;
    public $documentCatalogue;
    
    public $equipmentId;
    public $isEditing = false;
    
    protected $rules = [
        'typeEquipment' => 'required|string|min:3',
        'description' => 'required|string|min:10',
        'prix' => 'required|numeric|min:0',
        'estNeuf' => 'boolean',
        'documentCatalogue' => 'nullable|image|max:1024', // Only required for new equipment
    ];
    
    public function mount($id = null)
    {
        if ($id) {
            $this->equipmentId = $id;
            $this->isEditing = true;
            $this->loadEquipment();
        }
    }
    
    public function loadEquipment()
    {
        $equipment = MaterielFermierAgricole::findOrFail($this->equipmentId);
        
        // Verify ownership
        $user = Auth::user();
        $fournisseurId = $user->fournisseur->id;
        if ($equipment->fournisseurId != $fournisseurId) {
            session()->flash('error', 'You do not have permission to edit this equipment.');
            return redirect()->route('equipment.manage');
        }
        
        $this->typeEquipment = $equipment->typeEquipment;
        $this->description = $equipment->description;
        $this->prix = $equipment->prix;
        $this->estNeuf = $equipment->estNeuf;
    }
    
    public function render()
    {
        $fournisseur = Auth::user()->fournisseur;
        
        $equipment = MaterielFermierAgricole::where('fournisseurId', $fournisseur->id)
                                         ->latest()
                                         ->paginate(10);
        
        return view('livewire.equipment-management', [
            'equipment' => $equipment
        ]);
    }
    
    public function saveEquipment()
    {
        if ($this->isEditing) {
            $this->rules['documentCatalogue'] = 'nullable|image|max:1024';
        } else {
            $this->rules['documentCatalogue'] = 'required|image|max:1024';
        }
        
        $this->validate();
        
        $fournisseur = Auth::user()->fournisseur;
        
        if ($this->isEditing) {
            $equipment = MaterielFermierAgricole::findOrFail($this->equipmentId);
            
            // Verify ownership
            if ($equipment->fournisseurId != $fournisseur->id) {
                session()->flash('error', 'You do not have permission to edit this equipment.');
                return;
            }
        } else {
            $equipment = new MaterielFermierAgricole();
            $equipment->fournisseurId = $fournisseur->id;
        }
        
        $equipment->typeEquipment = $this->typeEquipment;
        $equipment->description = $this->description;
        $equipment->prix = $this->prix;
        $equipment->estNeuf = $this->estNeuf;
        
        if ($this->documentCatalogue) {
            // Delete old image if updating
            if ($this->isEditing && $equipment->documentCatalogue) {
                Storage::disk('public')->delete($equipment->documentCatalogue);
            }
            
            $imagePath = $this->documentCatalogue->store('equipment', 'public');
            $equipment->documentCatalogue = $imagePath;
        }
        
        $equipment->save();
        
        $this->reset(['typeEquipment', 'description', 'prix', 'estNeuf', 'documentCatalogue', 'isEditing', 'equipmentId']);
        
        session()->flash('message', $this->isEditing ? 'Equipment updated successfully!' : 'Equipment added successfully!');
    }
    
    public function editEquipment($id)
    {
        $this->isEditing = true;
        $this->equipmentId = $id;
        
        $equipment = MaterielFermierAgricole::findOrFail($id);
        
        // Verify ownership
        $user = Auth::user();
        $fournisseurId = $user->fournisseur->id;
        if ($equipment->fournisseurId != $fournisseurId) {
            session()->flash('error', 'You do not have permission to edit this equipment.');
            return;
        }
        
        $this->typeEquipment = $equipment->typeEquipment;
        $this->description = $equipment->description;
        $this->prix = $equipment->prix;
        $this->estNeuf = $equipment->estNeuf;
    }
    
    public function cancelEdit()
    {
        $this->reset(['typeEquipment', 'description', 'prix', 'estNeuf', 'documentCatalogue', 'isEditing', 'equipmentId']);
    }
    
    public function deleteEquipment($id)
    {
        $equipment = MaterielFermierAgricole::findOrFail($id);
        
        // Verify ownership
        $user = Auth::user();
        $fournisseurId = $user->fournisseur->id;
        if ($equipment->fournisseurId != $fournisseurId) {
            session()->flash('error', 'You do not have permission to delete this equipment.');
            return;
        }
        
        // Check if there are any pending transactions
        $hasPendingTransactions = $equipment->transactions()->where('statut', '!=', 'Cancelled')->exists();
        
        if ($hasPendingTransactions) {
            session()->flash('error', 'Cannot delete equipment with pending transactions.');
            return;
        }
        
        // Delete the image file
        if ($equipment->documentCatalogue) {
            Storage::disk('public')->delete($equipment->documentCatalogue);
        }
        
        $equipment->delete();
        
        session()->flash('message', 'Equipment deleted successfully!');
    }
    
    // This is necessary for using the component directly in a route
    public function __invoke()
    {
        return $this->render();
    }
}