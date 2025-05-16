<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TerrainAgricole;

class LandSearch extends Component
{
    public $search = '';
    public $minPrice;
    public $maxPrice;
    public $minSize;
    public $maxSize;
    public $location;
    
    public function render()
    {
        $query = TerrainAgricole::query();
        
        if ($this->search) {
            $query->where('description', 'like', '%' . $this->search . '%');
        }
        
        if ($this->minPrice) {
            $query->where('prix', '>=', $this->minPrice);
        }
        
        if ($this->maxPrice) {
            $query->where('prix', '<=', $this->maxPrice);
        }
        
        if ($this->minSize) {
            $query->where('superficie', '>=', $this->minSize);
        }
        
        if ($this->maxSize) {
            $query->where('superficie', '<=', $this->maxSize);
        }
        
        if ($this->location) {
            $query->where('adresse', 'like', '%' . $this->location . '%');
        }
        
        $lands = $query->paginate(9);
        
        return view('livewire.land-search', [
            'lands' => $lands
        ]);
    }
    
    public function resetFilters()
    {
        $this->search = '';
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->minSize = null;
        $this->maxSize = null;
        $this->location = null;
    }
    
    // This is necessary for using the component directly in a route
    public function __invoke()
    {
        return $this->render();
    }
}