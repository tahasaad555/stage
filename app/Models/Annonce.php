<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    // Explicitly specify the table name
    protected $table = 'annonces';

    protected $fillable = [
        'fournisseur_id',
        'terre_agricole_id',
        'title',
        'description',
        'is_active',
        'is_featured',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function terreAgricole()
    {
        return $this->belongsTo(TerreAgricole::class, 'terre_agricole_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}