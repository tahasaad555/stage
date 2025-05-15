<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Land routes
Route::get('/lands', function () {
    return view('lands.search');
})->name('lands.search');

Route::get('/lands/{id}', function ($id) {
    return view('lands.detail', ['id' => $id]);
})->name('lands.detail');

// Equipment routes
Route::get('/equipment', function () {
    return view('equipment.search');
})->name('equipment.search');

Route::get('/equipment/{id}', function ($id) {
    return view('equipment.detail', ['id' => $id]);
})->name('equipment.detail');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Profile management
    Route::get('/profile-management', function () {
        return view('profile-management');
    })->name('profile.management');
    
    // Favorites
    Route::get('/favorites', function () {
        return view('favorites');
    })->name('favorites');
    
    // Notifications
    Route::get('/notifications', function () {
        return view('notifications');
    })->name('notifications');
    
    // Messaging system
    Route::get('/messages/inbox', function () {
        return view('messages.inbox');
    })->name('messages.inbox');
    
    Route::get('/messages/sent', function () {
        return view('messages.sent');
    })->name('messages.sent');
    
    // Transactions
    Route::get('/transactions', function () {
        return view('transactions');
    })->name('transactions');
    
    Route::get('/orders', function () {
        return view('orders');
    })->name('orders');
    
    // Announcements
    Route::get('/announcements/create', function () {
        return view('announcements.create');
    })->name('announcements.create');
    
    Route::get('/my-announcements', function () {
        return view('announcements.manage');
    })->name('announcements.manage');
});

// Client-specific routes
Route::middleware(['auth', 'client'])->group(function () {
    Route::get('/my-lands', function () {
        return view('lands.manage');
    })->name('lands.manage');
});

// Supplier-specific routes
Route::middleware(['auth', 'supplier'])->group(function () {
    Route::get('/my-equipment', function () {
        return view('equipment.manage');
    })->name('equipment.manage');
});

// Admin-specific routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/users', function () {
        return view('admin.users');
    })->name('admin.users');
    
    Route::get('/announcements', function () {
        return view('admin.announcements');
    })->name('admin.announcements');
    
    Route::get('/transactions', function () {
        return view('admin.transactions');
    })->name('admin.transactions');
    
    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('admin.reports');
    
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');
    
    Route::get('/commissions', function () {
        return view('admin.commissions');
    })->name('admin.commissions');
});

require __DIR__.'/auth.php';