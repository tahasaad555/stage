<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
  use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'role',
        'is_active',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Role helpers
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function isFournisseur()
    {
        return $this->role === 'fournisseur';
    }

    // Core Relationships
    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function fournisseur()
    {
        return $this->hasOne(Fournisseur::class);
    }

    // ✅ FIXED: Property relationships through Fournisseur model
    public function properties()
    {
        return $this->hasManyThrough(Annonce::class, Fournisseur::class, 'user_id', 'fournisseur_id');
    }

    public function activeProperties()
    {
        return $this->properties()->where('is_active', true);
    }

    public function featuredProperties()
    {
        return $this->properties()->where('is_featured', true);
    }

    // Alternative French names for clarity
    public function annonces()
    {
        return $this->hasManyThrough(Annonce::class, Fournisseur::class, 'user_id', 'fournisseur_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
     /**
     * Get the saved properties for the user.
     */
    public function savedProperties(): HasMany
    {
        return $this->hasMany(SavedProperty::class);
    }

    /**
     * Get the inquiries sent by this client.
     */
    public function clientInquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'client_user_id');
    }

    /**
     * Get the inquiries received by this supplier.
     */
    public function supplierInquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'supplier_user_id');
    }

    /**
     * Check if user has saved a specific property/annonce.
     */
    public function hasSavedProperty($propertyId): bool
    {
        return $this->savedProperties()
            ->where('property_id', $propertyId)
            ->exists();
    }

    /**
     * Save a property/annonce for this user.
     */
    public function saveProperty($propertyId): SavedProperty
    {
        return $this->savedProperties()->firstOrCreate([
            'property_id' => $propertyId
        ]);
    }

    /**
     * Unsave a property/annonce for this user.
     */
    public function unsaveProperty($propertyId): bool
    {
        return $this->savedProperties()
            ->where('property_id', $propertyId)
            ->delete();
    }

    /**
     * Get user's initials for avatar display.
     */
    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }
}