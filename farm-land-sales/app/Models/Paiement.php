<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'methode',
        'dateTime',
        'total',
        'verified',
        'transactionId'
    ];

    protected $casts = [
        'dateTime' => 'datetime',
        'total' => 'double',
        'verified' => 'boolean',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactionId');
    }
}