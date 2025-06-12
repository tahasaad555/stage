<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AgriculturalLandController;
use App\Http\Controllers\Admin\ListingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Supplier\SupplierDashboardController;
use App\Http\Controllers\Supplier\PropertyController; // Add this line
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home page - redirect authenticated users to appropriate dashboard
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        } elseif ($user->isClient()) {
            return redirect('/client/dashboard');
        } elseif ($user->isFournisseur()) {
            return redirect('/supplier/dashboard');
        }
    }
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Guest routes (not authenticated)
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Registration routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Password reset routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Email verification routes
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (Request $request) {
        $request->fulfill();
        return redirect('/dashboard')->with('success', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Users - Complete CRUD Operations
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
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
    
    // Transactions - Complete Management with Export and Bulk Operations
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    
    // Export and Bulk Operations
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::post('/transactions/generate-report', [TransactionController::class, 'generateReport'])->name('transactions.generate-report');
    Route::post('/transactions/bulk-update', [TransactionController::class, 'bulkUpdate'])->name('transactions.bulk-update');
    Route::post('/transactions/reconcile', [TransactionController::class, 'reconcile'])->name('transactions.reconcile');
    
    // Individual Transaction Operations
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/send-notification', [TransactionController::class, 'sendNotification'])->name('transactions.send-notification');
    Route::get('/transactions/{transaction}/export-pdf', [TransactionController::class, 'exportPdf'])->name('transactions.export-pdf');
    Route::post('/transactions/{transaction}/update-status', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
});

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ClientDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [ClientDashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Add more client routes here as needed
    // Route::get('/properties', [ClientPropertyController::class, 'index'])->name('properties.index');
    // Route::get('/properties/{property}', [ClientPropertyController::class, 'show'])->name('properties.show');
    // Route::post('/properties/{property}/inquire', [ClientPropertyController::class, 'inquire'])->name('properties.inquire');
});

/*
|--------------------------------------------------------------------------
| Supplier Routes (Fournisseur)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/dashboard', [SupplierDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [SupplierDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [SupplierDashboardController::class, 'updateProfile'])->name('profile.update');
    
   // Properties Routes
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
Route::post('/properties/{property}/toggle-status', [PropertyController::class, 'toggleStatus'])->name('properties.toggle-status');
Route::post('/properties/{property}/toggle-featured', [PropertyController::class, 'toggleFeatured'])->name('properties.toggle-featured');

    // Dashboard API endpoints
    Route::get('/api/dashboard-summary', [SupplierDashboardController::class, 'getDashboardSummary'])->name('api.dashboard-summary');
    Route::get('/api/quick-stats', [SupplierDashboardController::class, 'getQuickStats'])->name('api.quick-stats');
});

// Legacy fournisseur routes for backward compatibility
Route::middleware(['auth'])->prefix('fournisseur')->name('fournisseur.')->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/supplier/dashboard');
    });
    Route::get('/profile', function () {
        return redirect('/supplier/profile');
    });
});

/*
|--------------------------------------------------------------------------
| Redirect Routes
|--------------------------------------------------------------------------
*/

// Redirect authenticated users to appropriate dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect('/admin/dashboard');
    } elseif ($user->isClient()) {
        return redirect('/client/dashboard');
    } elseif ($user->isFournisseur()) {
        return redirect('/supplier/dashboard');
    }
    
    return redirect('/');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| API Routes for AJAX
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Additional API routes for dashboard stats, search, etc.
    Route::get('/dashboard/stats', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return response()->json([
                'total_users' => \App\Models\User::count(),
                'total_clients' => \App\Models\User::where('role', 'client')->count(),
                'total_fournisseurs' => \App\Models\User::where('role', 'fournisseur')->count(),
                'active_users' => \App\Models\User::where('is_active', true)->count(),
            ]);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    });
});

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/

// Handle 404 errors gracefully
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
