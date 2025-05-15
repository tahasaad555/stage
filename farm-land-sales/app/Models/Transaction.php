<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'dateTransaction',
        'montant',
        'statut',
        'methodePaiement',
        'commission',
        'annonceId',
        'clientId'
    ];

    protected $casts = [
        'dateTransaction' => 'datetime',
        'montant' => 'double',
        'commission' => 'double',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'clientId');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'annonceId');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'transactionId');
    }

    public function materiel()
{
    return $this->belongsTo(MaterielFermierAgricole::class, 'materielId');
}
}