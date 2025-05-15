<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'commentaire',
        'clientId',
        'terrainId',
        'dateSoumission'
    ];

    protected $casts = [
        'note' => 'integer',
        'dateSoumission' => 'datetime'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'clientId');
    }

    public function terrain()
    {
        return $this->belongsTo(TerrainAgricole::class, 'terrainId');
    }
}