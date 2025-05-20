<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateAdminController extends Controller
{
    public function index()
    {
        try {
            // Insert utilisateur
            $id = DB::table('utilisateurs')->insertGetId([
                'nom' => 'Admin',
                'prenom' => 'System',
                'email' => 'admin@example.com',
                'motDePasse' => Hash::make('password123'),
                'subscribe' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Insert administrateur
            DB::table('administrateurs')->insert([
                'utilisateur_id' => $id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return "Admin user created successfully! Email: admin@example.com, Password: password123";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}