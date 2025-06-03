<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\TerreAgricole;
use App\Models\Annonce;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample clients
        $clientUsers = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'email' => 'jean.dupont@example.com',
                'specialization_type' => 'Organic Farming',
                'preferences' => 'Looking for fertile land near water sources'
            ],
            [
                'first_name' => 'Marie',
                'last_name' => 'Martin',
                'email' => 'marie.martin@example.com',
                'specialization_type' => 'Livestock',
                'preferences' => 'Prefer grassland suitable for cattle'
            ],
            [
                'first_name' => 'Pierre',
                'last_name' => 'Bernard',
                'email' => 'pierre.bernard@example.com',
                'specialization_type' => 'Cereal Crops',
                'preferences' => 'Flat terrain with good soil quality'
            ]
        ];

        foreach ($clientUsers as $clientData) {
            $user = User::create([
                'first_name' => $clientData['first_name'],
                'last_name' => $clientData['last_name'],
                'email' => $clientData['email'],
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            Client::create([
                'user_id' => $user->id,
                'specialization_type' => $clientData['specialization_type'],
                'preferences' => $clientData['preferences'],
            ]);
        }

        // Create sample suppliers
        $supplierUsers = [
            [
                'first_name' => 'François',
                'last_name' => 'Leroy',
                'email' => 'francois.leroy@example.com',
                'company_name' => 'Leroy Agricultural Holdings',
                'business_registration' => 'FR123456789',
                'address' => '123 Rue de la Ferme, 33000 Bordeaux, France'
            ],
            [
                'first_name' => 'Catherine',
                'last_name' => 'Moreau',
                'email' => 'catherine.moreau@example.com',
                'company_name' => 'Moreau Estates',
                'business_registration' => 'FR987654321',
                'address' => '456 Avenue des Vignes, 21000 Dijon, France'
            ],
            [
                'first_name' => 'Laurent',
                'last_name' => 'Dubois',
                'email' => 'laurent.dubois@example.com',
                'company_name' => 'Dubois Land Co.',
                'business_registration' => 'FR456789123',
                'address' => '789 Chemin des Champs, 31000 Toulouse, France'
            ]
        ];

        foreach ($supplierUsers as $supplierData) {
            $user = User::create([
                'first_name' => $supplierData['first_name'],
                'last_name' => $supplierData['last_name'],
                'email' => $supplierData['email'],
                'password' => Hash::make('password123'),
                'role' => 'fournisseur',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            Fournisseur::create([
                'user_id' => $user->id,
                'company_name' => $supplierData['company_name'],
                'business_registration' => $supplierData['business_registration'],
                'address' => $supplierData['address'],
            ]);
        }

        // Create sample agricultural lands
        $lands = [
            [
                'title' => 'Premium Vineyard Land in Bordeaux',
                'description' => 'Exceptional vineyard land in the heart of Bordeaux wine region. Rich soil composition perfect for premium wine grape cultivation. Includes existing drainage system and storage facilities.',
                'surface' => 15.5,
                'price' => 750000,
                'region' => 'Bordeaux',
                'country' => 'France',
                'gps_coordinates' => '44.8378, -0.5792',
                'soil_type' => 'Clay-limestone',
                'status' => 'available'
            ],
            [
                'title' => 'Organic Farming Land - Loire Valley',
                'description' => 'Certified organic farming land in the beautiful Loire Valley. Currently used for vegetable production. Includes greenhouse facilities and irrigation system.',
                'surface' => 8.2,
                'price' => 320000,
                'region' => 'Loire Valley',
                'country' => 'France',
                'gps_coordinates' => '47.2692, 1.0997',
                'soil_type' => 'Sandy loam',
                'status' => 'available'
            ],
            [
                'title' => 'Cattle Ranch - Normandy',
                'description' => 'Large cattle ranch in Normandy with excellent pasture land. Includes barns, milking facilities, and farmhouse. Perfect for dairy or beef cattle operations.',
                'surface' => 45.0,
                'price' => 1200000,
                'region' => 'Normandy',
                'country' => 'France',
                'gps_coordinates' => '49.1859, -0.3707',
                'soil_type' => 'Fertile grassland',
                'status' => 'available'
            ],
            [
                'title' => 'Cereal Farm - Champagne Region',
                'description' => 'Prime agricultural land suitable for wheat, barley, and other cereal crops. Flat terrain with modern irrigation. Easy access to major transportation routes.',
                'surface' => 28.7,
                'price' => 580000,
                'region' => 'Champagne',
                'country' => 'France',
                'gps_coordinates' => '49.0431, 4.0367',
                'soil_type' => 'Loamy',
                'status' => 'sold'
            ],
            [
                'title' => 'Mixed Farming Land - Provence',
                'description' => 'Versatile agricultural land in sunny Provence. Suitable for various crops including herbs, vegetables, and fruit trees. Includes well water access.',
                'surface' => 12.3,
                'price' => 420000,
                'region' => 'Provence',
                'country' => 'France',
                'gps_coordinates' => '43.9493, 4.8055',
                'soil_type' => 'Mediterranean',
                'status' => 'reserved'
            ]
        ];

        foreach ($lands as $landData) {
            TerreAgricole::create($landData);
        }

        // Create sample listings
        $fournisseurs = Fournisseur::all();
        $availableLands = TerreAgricole::where('status', 'available')->get();

        foreach ($availableLands as $index => $land) {
            $fournisseur = $fournisseurs->get($index % $fournisseurs->count());
            
            Annonce::create([
                'fournisseur_id' => $fournisseur->id,
                'terre_agricole_id' => $land->id,
                'title' => $land->title . ' - Now Available',
                'description' => $land->description . ' Contact us for viewing appointments and detailed information.',
                'is_active' => $index < 2, // Make first 2 active
                'is_featured' => $index == 0, // Make first one featured
                'published_at' => $index < 2 ? now()->subDays(rand(1, 30)) : null,
            ]);
        }

        // Create sample transactions
        $clients = Client::all();
        $allLands = TerreAgricole::all();

        $transactions = [
            [
                'client_id' => $clients->get(0)->id,
                'fournisseur_id' => $fournisseurs->get(0)->id,
                'terre_agricole_id' => $allLands->get(3)->id, // The sold land
                'amount' => 580000,
                'commission' => 29000,
                'status' => 'completed',
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'TXN-2024-001',
                'completed_at' => now()->subDays(5),
            ],
            [
                'client_id' => $clients->get(1)->id,
                'fournisseur_id' => $fournisseurs->get(1)->id,
                'terre_agricole_id' => $allLands->get(4)->id, // The reserved land
                'amount' => 420000,
                'commission' => 21000,
                'status' => 'pending',
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'TXN-2024-002',
                'completed_at' => null,
            ],
            [
                'client_id' => $clients->get(2)->id,
                'fournisseur_id' => $fournisseurs->get(2)->id,
                'terre_agricole_id' => $allLands->get(0)->id,
                'amount' => 750000,
                'commission' => 37500,
                'status' => 'pending',
                'payment_method' => 'wire_transfer',
                'payment_reference' => 'TXN-2024-003',
                'completed_at' => null,
            ],
            [
                'client_id' => $clients->get(0)->id,
                'fournisseur_id' => $fournisseurs->get(1)->id,
                'terre_agricole_id' => $allLands->get(1)->id,
                'amount' => 320000,
                'commission' => 16000,
                'status' => 'failed',
                'payment_method' => 'credit_card',
                'payment_reference' => 'TXN-2024-004',
                'completed_at' => null,
            ],
        ];

        foreach ($transactions as $transactionData) {
            Transaction::create(array_merge($transactionData, [
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]));
        }

        $this->command->info('Sample data created successfully!');
        $this->command->info('Created:');
        $this->command->info('- 3 Client users');
        $this->command->info('- 3 Supplier users');
        $this->command->info('- 5 Agricultural lands');
        $this->command->info('- 3 Listings');
        $this->command->info('- 4 Transactions');
        $this->command->info('');
        $this->command->info('Sample login credentials:');
        $this->command->info('Admin: admin@agriterre.com / password123');
        $this->command->info('Client: jean.dupont@example.com / password123');
        $this->command->info('Supplier: francois.leroy@example.com / password123');
    }
}