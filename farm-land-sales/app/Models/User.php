<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // Add these relationships to the existing User model
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
