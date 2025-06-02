<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    // Explicitly specify the table name
    protected $table = 'fournisseurs';

    protected $fillable = [
        'user_id',
        'company_name',
        'business_registration',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}