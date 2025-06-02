<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Explicitly specify the table name
    protected $table = 'transactions';

    protected $fillable = [
        'client_id',
        'fournisseur_id',
        'terre_agricole_id',
        'amount',
        'commission',
        'status',
        'payment_method',
        'payment_reference',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'commission' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
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
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}