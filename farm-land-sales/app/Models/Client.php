<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'telephone',
        'preferences'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'clientId');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'clientId');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'expediteurId')
                    ->orWhere('destinataireId', $this->id);
    }

    public function terrainsPreferes()
    {
        return $this->belongsToMany(TerrainAgricole::class, 'favoris', 'client_id', 'terrain_id');
    }
}