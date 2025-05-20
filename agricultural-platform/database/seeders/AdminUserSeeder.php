<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $utilisateur = \App\Models\Utilisateur::create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'email' => 'admin@example.com',
            'motDePasse' => 'password123',
            'subscribe' => false,
        ]);

        \App\Models\Administrateur::create([
            'utilisateur_id' => $utilisateur->id,
        ]);
    }
}