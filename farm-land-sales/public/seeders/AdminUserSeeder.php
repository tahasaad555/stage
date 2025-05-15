<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Administrateur;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@agriland.com',
            'password' => Hash::make('password'),
        ]);
        
        // Assign admin role
        Administrateur::create([
            'user_id' => $user->id
        ]);
    }
}