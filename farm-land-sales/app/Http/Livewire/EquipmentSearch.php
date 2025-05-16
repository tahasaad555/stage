<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MaterielFermierAgricole;

class EquipmentSearch extends Component
{
    public $search = '';
    public $minPrice;
    public $maxPrice;
    public $category;
    public $condition;
    
    public function render()
    {
        $query = MaterielFermierAgricole::query();
        
        if ($this->search) {
            $query->where('typeEquipment', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }
        
        if ($this->minPrice) {
            $query->where('prix', '>=', $this->minPrice);
        }
        
        if ($this->maxPrice) {
            $query->where('prix', '<=', $this->maxPrice);
        }
        
        if ($this->category) {
            $query->where('typeEquipment', $this->category);
        }
        
        if ($this->condition) {
            $query->where('estNeuf', $this->condition === 'new');
        }
        
        $equipment = $query->paginate(9);
        
        return view('livewire.equipment-search', [
            'equipment' => $equipment
        ]);
    }
    
    public function resetFilters()
    {
        $this->search = '';
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->category = null;
        $this->condition = null;
    }
    
    // This is necessary for using the component directly in a route
    public function __invoke()
    {
        return $this->render();
    }
}