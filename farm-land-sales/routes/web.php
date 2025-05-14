<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Home;
use App\Http\Livewire\LandSearch;
use App\Http\Livewire\EquipmentSearch;
use App\Http\Livewire\LandDetail;
use App\Http\Livewire\CreateAnnouncement;
use App\Http\Livewire\MessagesInbox;
use App\Http\Livewire\TransactionManagement;
use App\Http\Livewire\SupplierDashboard;
use App\Http\Livewire\EquipmentManagement;
use App\Http\Livewire\OrderManagement;
use App\Http\Livewire\ClientDashboard;
use App\Http\Livewire\Admin\PendingAnnouncements;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', App\Http\Livewire\Home::class)->name('home');

// Authentication routes
require __DIR__.'/auth.php';

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard routes - redirects based on user role
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    // Land routes
    Route::get('/lands', LandSearch::class)->name('lands.search');
    Route::get('/lands/{id}', LandDetail::class)->name('lands.detail');
    
    // Client-specific routes
    Route::middleware(['client'])->group(function () {
        Route::get('/client/dashboard', ClientDashboard::class)->name('client.dashboard');
        Route::get('/my-lands', App\Http\Livewire\MyLandsManagement::class)->name('lands.manage');
    });
    
    // Equipment routes
    Route::get('/equipment', EquipmentSearch::class)->name('equipment.search');
    Route::get('/equipment/{id}', App\Http\Livewire\EquipmentDetail::class)->name('equipment.detail');
    
    // Supplier-specific routes
    Route::middleware(['supplier'])->group(function () {
        Route::get('/supplier/dashboard', SupplierDashboard::class)->name('supplier.dashboard');
        Route::get('/equipment/create', EquipmentManagement::class)->name('equipment.create');
        Route::get('/equipment/{id}/edit', EquipmentManagement::class)->name('equipment.edit');
        Route::get('/my-equipment', EquipmentManagement::class)->name('equipment.manage');
    });
    
    // Announcement routes
    Route::get('/announcements/create', CreateAnnouncement::class)->name('announcements.create');
    Route::get('/my-announcements', App\Http\Livewire\MyAnnouncementsManagement::class)->name('announcements.manage');
    
    // Message routes
    Route::get('/messages', MessagesInbox::class)->name('messages.inbox');
    Route::get('/messages/sent', App\Http\Livewire\MessagesSent::class)->name('messages.sent');
    
    // Transaction routes
    Route::get('/transactions', TransactionManagement::class)->name('transactions');
    
    // Order routes
    Route::get('/orders', OrderManagement::class)->name('orders.index');
    Route::get('/orders/{id}', OrderManagement::class)->name('orders.detail');
    
    // Favorites routes
    Route::get('/favorites', App\Http\Livewire\FavoritesManagement::class)->name('favorites');
    
    // Notifications routes
    Route::get('/notifications', App\Http\Livewire\NotificationsComponent::class)->name('notifications');
    
    // User profile management
    Route::get('/profile', App\Http\Livewire\ProfileManagement::class)->name('profile');
    
    // Admin routes
    Route::prefix('admin')->middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', App\Http\Livewire\Admin\AdminDashboard::class)->name('admin.dashboard');
        Route::get('/reports', App\Http\Livewire\Admin\Reports::class)->name('admin.reports');
        Route::get('/settings', App\Http\Livewire\Admin\SystemSettings::class)->name('admin.settings');
        Route::get('/users', App\Http\Livewire\Admin\UserManagement::class)->name('admin.users');
        Route::get('/announcements', App\Http\Livewire\Admin\PendingAnnouncements::class)->name('admin.announcements');
        Route::get('/transactions', App\Http\Livewire\Admin\TransactionManagement::class)->name('admin.transactions');
        Route::get('/commissions', App\Http\Livewire\Admin\CommissionManagement::class)->name('admin.commissions');
    });
});