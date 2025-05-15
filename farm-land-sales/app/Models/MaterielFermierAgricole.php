<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterielFermierAgricole extends Model
{
    use HasFactory;

    protected $table = 'materiels_fermier_agricoles';

    protected $fillable = [
        'typeEquipment',
        'estNeuf',
        'description',
        'prix',
        'documentCatalogue',
        'fournisseurId',
        'annonceId'
    ];

    protected $casts = [
        'estNeuf' => 'boolean',
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'annonceId');
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseurId');
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'materielId');
    }
}