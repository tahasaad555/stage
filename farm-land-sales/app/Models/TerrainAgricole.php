<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerrainAgricole extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'titre',
        'description',
        'adresse',
        'superficie',
        'prix',
        'region',
        'coordonneesGPS',
        'statut',
        'proprietaireId',
        'images'
    ];

    public function proprietaire()
    {
        return $this->belongsTo(Client::class, 'proprietaireId');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'annonceId');
    }

    public function systemesIrrigation()
    {
        return $this->hasMany(SystemeIrrigation::class, 'terrainId');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'terrainId');
    }

    public function clientsFavoris()
    {
        return $this->belongsToMany(Client::class, 'favoris', 'terrain_id', 'client_id');
    }
}