<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * Get the client record associated with the user.
     */
    public function client()
    {
        return $this->hasOne(Client::class);
    }
    
    /**
     * Get the fournisseur (supplier) record associated with the user.
     */
    public function fournisseur()
    {
        return $this->hasOne(Fournisseur::class);
    }
    
    /**
     * Get the administrateur (admin) record associated with the user.
     */
    public function administrateur()
    {
        return $this->hasOne(Administrateur::class);
    }
    
    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    
    /**
     * Check if user has a specific role.
     */
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