<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerrainAgricole extends Model
{
    use HasFactory;

    protected $table = 'terrains_agricoles';

    protected $fillable = [
        'titre',
        'description',
        'adresse',
        'superficie',
        'prix',
        'region',
        'coordonneesGPS',
        'statut',
        'proprietaireId',
        'images',
        'type',
        'annonceId'
    ];

    public function proprietaire()
    {
        return $this->belongsTo(Client::class, 'proprietaireId');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'annonceId');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'terrainId');
    }

    public function clientsFavoris()
    {
        return $this->belongsToMany(Client::class, 'favoris', 'terrain_id', 'client_id');
    }
    
    public function transactions()
    {
        return $this->hasManyThrough(
            Transaction::class,
            Annonce::class,
            'annonceId',
            'id',
            'id',
            'id'
        );
    }
}