<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function fournisseur()
    {
        return $this->hasOne(Fournisseur::class);
    }

    public function administrateur()
    {
        return $this->hasOne(Administrateur::class);
    }

    public function hasRole($role)
    {
        if ($role == 'admin' && $this->administrateur) {
            return true;
        } elseif ($role == 'client' && $this->client) {
            return true;
        } elseif ($role == 'fournisseur' && $this->fournisseur) {
            return true;
        }
        
        return false;
    }
}