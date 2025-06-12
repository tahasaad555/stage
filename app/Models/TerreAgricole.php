<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerreAgricole extends Model
{
    use HasFactory;

    protected $table = 'terres_agricoles';

    protected $fillable = [
        'title',
        'description',
        'surface',
        'price',
        'region',
        'country',
        'localisation',
        'gps_coordinates',
        'soil_type',
        'status',
        'photos',
        'assigned_supplier_id', // ✅ Add this
    ];

    protected function casts(): array
    {
        return [
            'photos' => 'array',
            'surface' => 'decimal:2',
            'price' => 'decimal:2',
        ];
    }

    // ✅ Add this relationship
    public function assignedSupplier()
    {
        return $this->belongsTo(Fournisseur::class, 'assigned_supplier_id');
    }

    public function annonce()
    {
        return $this->hasOne(Annonce::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'terre_agricole_id');
    }

    public function listings()
    {
        return $this->hasMany(Annonce::class, 'terre_agricole_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // ✅ Add this scope
    public function scopeAssignedTo($query, $supplierId)
    {
        return $query->where('assigned_supplier_id', $supplierId);
    }
}