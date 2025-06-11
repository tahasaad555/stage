<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerreAgricole extends Model
{
    use HasFactory;

    // Explicitly specify the table name
    protected $table = 'terres_agricoles';

    protected $fillable = [
        'title',
        'description',
        'surface',
        'price',
        'region',
        'country',
        'gps_coordinates',
        'soil_type',
        'status',
        'photos',
    ];

    protected function casts(): array
    {
        return [
            'photos' => 'array',
            'surface' => 'decimal:2',
            'price' => 'decimal:2',
        ];
    }

    public function annonce()
    {
        return $this->hasOne(Annonce::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
        /**
     * Get all annonces (listings) for this land
     */
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'terre_agricole_id');
    }

    /**
     * Alternative relationship name for clarity
     */
    public function listings()
    {
        return $this->hasMany(Annonce::class, 'terre_agricole_id');
    }
}