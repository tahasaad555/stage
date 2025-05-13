<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'titre',
        'type',
        'description',
        'prix',
        'dateCreation',
        'estActif',
        'image',
        'clientId'
    ];

    protected $casts = [
        'dateCreation' => 'datetime',
        'estActif' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'clientId');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'annonceId');
    }

    public function terrain()
    {
        return $this->hasOne(TerrainAgricole::class, 'annonceId');
    }

    public function materiel()
    {
        return $this->hasOne(MaterielFermierAgricole::class, 'annonceId');
    }
}