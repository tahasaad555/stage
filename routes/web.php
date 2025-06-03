<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AgriculturalLandController;
use App\Http\Controllers\Admin\ListingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home page - simple welcome message
Route::get('/', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect('/admin/dashboard');
    }
    return view('welcome');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Agricultural Lands
    Route::get('/lands', [AgriculturalLandController::class, 'index'])->name('lands.index');
    Route::get('/lands/create', [AgriculturalLandController::class, 'create'])->name('lands.create');
    Route::post('/lands', [AgriculturalLandController::class, 'store'])->name('lands.store');
    Route::get('/lands/{land}', [AgriculturalLandController::class, 'show'])->name('lands.show');
    Route::get('/lands/{land}/edit', [AgriculturalLandController::class, 'edit'])->name('lands.edit');
    Route::put('/lands/{land}', [AgriculturalLandController::class, 'update'])->name('lands.update');
    Route::post('/lands/{land}/update-status', [AgriculturalLandController::class, 'updateStatus'])->name('lands.update-status');
    Route::delete('/lands/{land}', [AgriculturalLandController::class, 'destroy'])->name('lands.destroy');
    
    // Listings
    Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
    Route::post('/listings/{listing}/toggle-status', [ListingController::class, 'toggleStatus'])->name('listings.toggle-status');
    Route::post('/listings/{listing}/toggle-featured', [ListingController::class, 'toggleFeatured'])->name('listings.toggle-featured');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');
    
    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/update-status', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
});