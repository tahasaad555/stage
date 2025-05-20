<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\AdministrateurController;
use App\Http\Controllers\TerreAgricoleController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\FournisseurDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home page
Route::get('/', function () {
    // Get featured listings for homepage
    $featuredAnnonces = \App\Models\Annonce::with(['terreAgricole', 'fournisseur.utilisateur'])
        ->where('estActive', true)
        ->latest('dateCreation')
        ->limit(6)
        ->get();
        
    return view('welcome', compact('featuredAnnonces'));
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    
    Route::get('/register/client', [AuthController::class, 'showClientRegistrationForm'])->name('register.client');
    Route::post('/register/client', [AuthController::class, 'registerClient'])->name('register.client.submit');
    
    Route::get('/register/fournisseur', [AuthController::class, 'showFournisseurRegistrationForm'])->name('register.fournisseur');
    Route::post('/register/fournisseur', [AuthController::class, 'registerFournisseur'])->name('register.fournisseur.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public routes
Route::get('/terres', [TerreAgricoleController::class, 'index'])->name('terres.index');
Route::get('/terres/{terre}', [TerreAgricoleController::class, 'show'])->name('terres.show');
Route::get('/terres/search', [TerreAgricoleController::class, 'search'])->name('terres.search');

Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');

// Protected routes for all authenticated users
Route::middleware('auth')->group(function () {
    // Routes for admin users
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        Route::resource('administrateurs', AdministrateurController::class);
        Route::resource('clients', ClientController::class);
        Route::resource('fournisseurs', FournisseurController::class);
    });
    
    // Routes for client users
    Route::middleware('client')->group(function () {
        Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
        
        // Client profile
        Route::get('/client/profile', [ClientController::class, 'profile'])->name('client.profile');
        Route::post('/client/profile', [ClientController::class, 'updateProfile'])->name('client.profile.update');
        
        // Panier (cart) routes
        Route::get('/panier', [PanierController::class, 'show'])->name('panier.show');
        Route::post('/panier/add-produit', [PanierController::class, 'addProduit'])->name('panier.add-produit');
        Route::post('/panier/add-terre', [PanierController::class, 'addTerre'])->name('panier.add-terre');
        Route::post('/panier/update-quantity/{ligneCommande}', [PanierController::class, 'updateQuantity'])->name('panier.update-quantity');
        Route::delete('/panier/remove-item/{ligneCommande}', [PanierController::class, 'removeItem'])->name('panier.remove-item');
        Route::post('/panier/empty', [PanierController::class, 'emptyCart'])->name('panier.empty');
        
        // Client transactions
        Route::get('/client/transactions', [TransactionController::class, 'index'])->name('client.transactions');
        Route::get('/client/transactions/create', [TransactionController::class, 'create'])->name('client.transactions.create');
        Route::post('/client/transactions', [TransactionController::class, 'store'])->name('client.transactions.store');
        Route::get('/client/transactions/{transaction}', [TransactionController::class, 'show'])->name('client.transactions.show');
        Route::post('/client/transactions/{transaction}/process-payment', [TransactionController::class, 'processPayment'])->name('client.transactions.process-payment');
        Route::post('/client/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('client.transactions.cancel');
        
        // Favoris (favorites)
        Route::post('/annonces/{annonce}/toggle-favorite', [AnnonceController::class, 'toggleFavorite'])->name('annonces.toggle-favorite');
        
        // Messages (client side)
        Route::get('/client/messages', [MessageController::class, 'index'])->name('client.messages');
        Route::get('/client/messages/conversation/{fournisseur}', [MessageController::class, 'conversation'])->name('client.messages.conversation');
        Route::post('/client/messages/send', [MessageController::class, 'send'])->name('client.messages.send');
        Route::post('/client/messages/mark-all-read', [MessageController::class, 'markAllAsRead'])->name('client.messages.mark-all-read');
    });
    
    // Routes for fournisseur users
    Route::middleware('fournisseur')->group(function () {
        Route::get('/fournisseur/dashboard', [FournisseurDashboardController::class, 'index'])->name('fournisseur.dashboard');
        
        // Fournisseur profile
        Route::get('/fournisseur/profile', [FournisseurController::class, 'profile'])->name('fournisseur.profile');
        Route::post('/fournisseur/profile', [FournisseurController::class, 'updateProfile'])->name('fournisseur.profile.update');
        
        // Terres agricoles management
        Route::get('/fournisseur/terres', [TerreAgricoleController::class, 'index'])->name('fournisseur.terres');
        Route::get('/fournisseur/terres/create', [TerreAgricoleController::class, 'create'])->name('fournisseur.terres.create');
        Route::post('/fournisseur/terres', [TerreAgricoleController::class, 'store'])->name('fournisseur.terres.store');
        Route::get('/fournisseur/terres/{terre}/edit', [TerreAgricoleController::class, 'edit'])->name('fournisseur.terres.edit');
        Route::put('/fournisseur/terres/{terre}', [TerreAgricoleController::class, 'update'])->name('fournisseur.terres.update');
        Route::delete('/fournisseur/terres/{terre}', [TerreAgricoleController::class, 'destroy'])->name('fournisseur.terres.destroy');
        Route::delete('/fournisseur/terres/{terre}/remove-photo', [TerreAgricoleController::class, 'removePhoto'])->name('fournisseur.terres.remove-photo');
        
        // Annonces management
        Route::get('/fournisseur/annonces', [AnnonceController::class, 'mesAnnonces'])->name('fournisseur.annonces');
        Route::get('/fournisseur/annonces/create', [AnnonceController::class, 'create'])->name('fournisseur.annonces.create');
        Route::post('/fournisseur/annonces', [AnnonceController::class, 'store'])->name('fournisseur.annonces.store');
        Route::get('/fournisseur/annonces/{annonce}/edit', [AnnonceController::class, 'edit'])->name('fournisseur.annonces.edit');
        Route::put('/fournisseur/annonces/{annonce}', [AnnonceController::class, 'update'])->name('fournisseur.annonces.update');
        Route::delete('/fournisseur/annonces/{annonce}', [AnnonceController::class, 'destroy'])->name('fournisseur.annonces.destroy');
        
        // Fournisseur transactions
        Route::get('/fournisseur/transactions', [TransactionController::class, 'index'])->name('fournisseur.transactions');
        Route::get('/fournisseur/transactions/{transaction}', [TransactionController::class, 'show'])->name('fournisseur.transactions.show');
        
        // Messages (fournisseur side)
        Route::get('/fournisseur/messages', [MessageController::class, 'index'])->name('fournisseur.messages');
        Route::get('/fournisseur/messages/conversation/{client}', [MessageController::class, 'conversation'])->name('fournisseur.messages.conversation');
        Route::post('/fournisseur/messages/send', [MessageController::class, 'send'])->name('fournisseur.messages.send');
        Route::post('/fournisseur/messages/mark-all-read', [MessageController::class, 'markAllAsRead'])->name('fournisseur.messages.mark-all-read');
    });
    
    // Common routes for transactions
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
});

// Admin resource routes (with auth middleware applied in the controllers)
Route::resource('terres', TerreAgricoleController::class)->except(['index', 'show']);
Route::resource('annonces', AnnonceController::class)->except(['index', 'show']);
Route::resource('transactions', TransactionController::class)->except(['show']);
Route::get('/create-admin', [App\Http\Controllers\CreateAdminController::class, 'index']);