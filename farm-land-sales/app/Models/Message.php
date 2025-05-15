<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'contenu',
        'dateEnvoi',
        'lu',
        'expediteurId',
        'destinataireId'
    ];

    protected $casts = [
        'dateEnvoi' => 'datetime',
        'lu' => 'boolean',
    ];

    public function expediteur()
    {
        return $this->belongsTo(Client::class, 'expediteurId');
    }

    public function destinataire()
    {
        return $this->belongsTo(Client::class, 'destinataireId');
    }
}