<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemCartographie extends Model
{
    use HasFactory;

    protected $fillable = [
        'coordonneesGPS',
        'zoom_level',
        'region'
    ];

    protected $casts = [
        'zoom_level' => 'integer',
        'calculDistance' => 'double'
    ];

    public function terrain()
    {
        return $this->belongsTo(TerrainAgricole::class);
    }

    public function afficherCarte()
    {
        // Implementation
    }

    public function genererPrevisualisation()
    {
        // Implementation
    }

    public function calculerDistance($lat, $lng)
    {
        // Implementation to calculate distance
        return 0.0;
    }
}