<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TerrainAgricole;
use App\Models\MaterielFermierAgricole;
use App\Models\Annonce;

class Home extends Component
{
    public function render()
    {
        $featuredLands = TerrainAgricole::latest()->take(3)->get();
        $featuredEquipment = MaterielFermierAgricole::latest()->take(3)->get();
        $totalAnnouncements = Annonce::count();
        $totalLands = TerrainAgricole::count();
        $totalEquipment = MaterielFermierAgricole::count();
        
        return view('livewire.home', [
            'featuredLands' => $featuredLands,
            'featuredEquipment' => $featuredEquipment,
            'totalAnnouncements' => $totalAnnouncements,
            'totalLands' => $totalLands,
            'totalEquipment' => $totalEquipment
        ]);
    }
}