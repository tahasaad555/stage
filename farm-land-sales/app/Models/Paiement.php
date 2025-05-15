<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'methode',
        'reference',
        'detail',
        'total',
        'verified',
        'complet',
        'transactionId',
        'dateTime'
    ];

    protected $casts = [
        'dateTime' => 'datetime',
        'total' => 'double',
        'verified' => 'boolean',
        'complet' => 'boolean',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactionId');
    }
}