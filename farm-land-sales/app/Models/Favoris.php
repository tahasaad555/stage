<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favoris extends Model
{
    use HasFactory;

    protected $table = 'favoris';
    
    protected $fillable = [
        'client_id',
        'terrain_id',
        'equipment_id',
        'type'  // 'terrain' or 'equipment'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function terrain()
    {
        return $this->belongsTo(TerrainAgricole::class, 'terrain_id');
    }

    public function equipment()
    {
        return $this->belongsTo(MaterielFermierAgricole::class, 'equipment_id');
    }
}