<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

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

    // Relationships
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Fournisseur::class, 'id', 'id', 'fournisseur_id', 'user_id');
    }

    public function terreAgricole()
    {
        return $this->belongsTo(TerreAgricole::class, 'terre_agricole_id');
    }

    public function agriculturalLand()
    {
        return $this->belongsTo(TerreAgricole::class, 'terre_agricole_id');
    }

    public function savedByUsers()
    {
        return $this->hasMany(SavedProperty::class, 'property_id');
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'property_id');
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

    public function scopeWithLandData($query)
    {
        return $query->with('terreAgricole');
    }

    // Helper Methods for Data Access
    public function getEffectiveTitle()
    {
        return $this->titre ?: $this->title ?: 'Untitled Property';
    }

    public function getEffectivePrice()
    {
        // Return annonce price if set, otherwise terre_agricole price
        return $this->prix ?: ($this->terreAgricole->price ?? 0);
    }

    public function getEffectivePriceFormatted()
    {
        $price = $this->getEffectivePrice();
        return number_format($price) . ' MAD';
    }

    public function getPricePerHectare()
    {
        $price = $this->getEffectivePrice();
        $area = $this->terreAgricole->surface ?? 0;
        
        if ($area > 0 && $price > 0) {
            return $price / $area;
        }
        
        return 0;
    }

    public function getPricePerHectareFormatted()
    {
        $pricePerHa = $this->getPricePerHectare();
        return $pricePerHa > 0 ? number_format($pricePerHa) . ' MAD/ha' : 'N/A';
    }

    public function getEffectiveLocation()
    {
        if ($this->terreAgricole) {
            $location = $this->terreAgricole->region;
            if ($this->terreAgricole->localisation && $this->terreAgricole->localisation !== $this->terreAgricole->region) {
                $location .= ', ' . $this->terreAgricole->localisation;
            }
            return $location;
        }
        return 'Location not specified';
    }

    public function getEffectiveArea()
    {
        return $this->terreAgricole->surface ?? 0;
    }

    public function getEffectiveAreaFormatted()
    {
        $area = $this->getEffectiveArea();
        return $area > 0 ? number_format($area, 1) . ' hectares' : 'Area not specified';
    }

    public function getSoilType()
    {
        return $this->terreAgricole->soil_type ?? null;
    }

    public function getSupplierName()
    {
        return $this->fournisseur->company_name ?? $this->fournisseur->user->full_name ?? 'N/A';
    }

    public function getMainImage()
    {
        if ($this->terreAgricole && $this->terreAgricole->photos && count($this->terreAgricole->photos) > 0) {
            return asset('storage/' . $this->terreAgricole->photos[0]);
        }
        return null;
    }

    public function hasImages()
    {
        return $this->terreAgricole && $this->terreAgricole->photos && count($this->terreAgricole->photos) > 0;
    }

    public function getAllImages()
    {
        if ($this->hasImages()) {
            return collect($this->terreAgricole->photos)->map(function($photo) {
                return asset('storage/' . $photo);
            });
        }
        return collect();
    }

    // Accessors for backward compatibility
    public function getTitleAttribute()
    {
        return $this->attributes['titre'] ?: $this->attributes['title'] ?? '';
    }

    public function getPriceAttribute()
    {
        return $this->attributes['prix'] ?: $this->attributes['price'] ?? 0;
    }

    // Mutators to handle both English and French field names
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

    // Status helpers
    public function isSavedByUser($userId = null)
    {
        $userId = $userId ?: auth()->id();
        return $this->savedByUsers()->where('user_id', $userId)->exists();
    }

    public function getStatusBadge()
    {
        if ($this->is_featured) {
            return '<span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium"><i class="fas fa-star mr-1"></i>Featured</span>';
        }
        
        if ($this->terreAgricole && $this->terreAgricole->status) {
            $statusColors = [
                'available' => 'bg-green-500',
                'sold' => 'bg-red-500',
                'reserved' => 'bg-orange-500',
                'pending' => 'bg-yellow-500'
            ];
            
            $colorClass = $statusColors[$this->terreAgricole->status] ?? 'bg-gray-500';
            return '<span class="' . $colorClass . ' text-white px-3 py-1 rounded-full text-sm font-medium capitalize">' . $this->terreAgricole->status . '</span>';
        }
        
        return '<span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">Available</span>';
    }
}