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
        'titre',            
        'title',            
        'description',
        'prix',             
        'price',            
        'is_active',
        'is_featured',
        'published_at',
        'statut',           
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'prix' => 'decimal:2',
            'price' => 'decimal:2',
        ];
    }

    // ✅ FIXED: Link to Fournisseur model, not User
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    // ✅ Get the user through the fournisseur relationship
    public function user()
    {
        return $this->hasOneThrough(User::class, Fournisseur::class, 'id', 'id', 'fournisseur_id', 'user_id');
    }

    public function terreAgricole()
    {
        return $this->belongsTo(TerreAgricole::class, 'terre_agricole_id');
    }

    // ✅ Alternative relationship name for clarity
    public function agriculturalLand()
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

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('fournisseur_id', $supplierId);
    }

    // ✅ Accessors for consistent access (English/French compatibility)
    public function getTitleAttribute()
    {
        return $this->attributes['titre'] ?: $this->attributes['title'] ?? '';
    }

    public function getPriceAttribute()
    {
        return $this->attributes['prix'] ?: $this->attributes['price'] ?? 0;
    }

    // ✅ Mutators to handle both English and French field names
    public function setTitleAttribute($value)
    {
        $this->attributes['titre'] = $value;
        $this->attributes['title'] = $value;
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['prix'] = $value;
        $this->attributes['price'] = $value;
    }
}