<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'entreprise',
        'telephone',
        'adresse',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materiels()
    {
        return $this->hasMany(MaterielFermierAgricole::class, 'fournisseurId');
    }

    public function modifierInventaire($id, $data)
    {
        // Implementation
    }

    public function supprimerMateriel($id, $soft = true)
    {
        // Implementation
    }

    public function gererStock($id, $quantite)
    {
        // Implementation
    }

    public function genererFacture()
    {
        // Implementation
    }

    public function suivreVente()
    {
        // Implementation
    }
}