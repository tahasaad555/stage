<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Administrateur;
use App\Models\TerrainAgricole;
use App\Models\MaterielFermierAgricole;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Check if required columns exist
        if (!Schema::hasColumn('users', 'name')) {
            $this->command->error('The users table is missing the name column. Please run migrations first.');
            return;
        }
        
        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@agriland.com',
            'password' => Hash::make('password'),
        ]);
        
        Administrateur::create([
            'user_id' => $adminUser->id
        ]);
        
        // Create client user
        $clientUser = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
        ]);
        
        $client = Client::create([
            'user_id' => $clientUser->id,
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'telephone' => '0612345678',
        ]);
        
        // Create supplier user
        $supplierUser = User::create([
            'name' => 'Supplier User',
            'email' => 'supplier@example.com',
            'password' => Hash::make('password'),
        ]);
        
        $fournisseur = Fournisseur::create([
            'user_id' => $supplierUser->id,
            'nom' => 'Martin',
            'prenom' => 'Pierre',
            'entreprise' => 'Agri Solutions',
            'telephone' => '0687654321',
            'adresse' => '123 Rue de la Ferme, 75000 Paris',
            'description' => 'Spécialiste en matériel agricole depuis 2005.',
        ]);
        
        // Create some agricultural lands
        TerrainAgricole::create([
            'titre' => 'Terrain fertile idéal pour cultures',
            'description' => 'Magnifique terrain agricole avec accès à l\'eau, idéal pour cultures diverses.',
            'adresse' => 'Route de Campagne, 33000 Bordeaux',
            'superficie' => 5000,
            'prix' => 50000,
            'region' => 'Aquitaine',
            'coordonneesGPS' => '44.837789,-0.579180',
            'statut' => 'available',
            'proprietaireId' => $client->id,
            'images' => null, // Add images later
            'type' => 'arable',
        ]);
        
        // Create some farming equipment
        MaterielFermierAgricole::create([
            'typeEquipment' => 'Tracteur John Deere',
            'estNeuf' => false,
            'description' => 'Tracteur John Deere 6130R, 2018, 2500 heures, excellent état.',
            'prix' => 75000,
            'documentCatalogue' => null, // Add images later
            'fournisseurId' => $fournisseur->id,
        ]);
        
        // Create system settings
        Setting::create([
            'key' => 'commission',
            'value' => '5',
        ]);
        
        Setting::create([
            'key' => 'site_name',
            'value' => 'AgriLand',
        ]);
    }
}