<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedProperty extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'property_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that saved the property.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the property that was saved - FIXED to use correct relationship
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Annonce::class, 'property_id', 'id');
    }

    /**
     * Alternative relationship name for clarity - FIXED
     */
    public function annonce(): BelongsTo
    {
        return $this->belongsTo(Annonce::class, 'property_id', 'id');
    }

    /**
     * Scope to get saved properties for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get recent saved properties.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to get saved properties with active listings only
     */
    public function scopeActiveProperties($query)
    {
        return $query->whereHas('property', function($q) {
            $q->where('is_active', true);
        });
    }
}