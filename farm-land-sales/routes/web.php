<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Home;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\LandSearch;
use App\Http\Livewire\LandDetail;
use App\Http\Livewire\EquipmentSearch;
use App\Http\Livewire\EquipmentDetail;
use App\Http\Livewire\ClientDashboard;
use App\Http\Livewire\SupplierDashboard;
use App\Http\Livewire\MyLandsManagement;
use App\Http\Livewire\EquipmentManagement;
use App\Http\Livewire\CreateAnnouncement;
use App\Http\Livewire\MyAnnouncementsManagement;
use App\Http\Livewire\MessagesInbox;
use App\Http\Livewire\MessagesSent;
use App\Http\Livewire\OrderManagement;
use App\Http\Livewire\TransactionManagement;
use App\Http\Livewire\FavoritesManagement;
use App\Http\Livewire\NotificationsComponent;
use App\Http\Livewire\ProfileManagement;
use App\Http\Livewire\Admin\AdminDashboard;
use App\Http\Livewire\Admin\Reports;
use App\Http\Livewire\Admin\SystemSettings;
use App\Http\Livewire\Admin\UserManagement;
use App\Http\Livewire\Admin\PendingAnnouncements;
use App\Http\Livewire\Admin\CommissionManagement;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', Home::class)->name('home');

// Authentication routes
require __DIR__.'/auth.php';

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard routes - redirects based on user role
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    // Land routes
    Route::get('/lands/search', LandSearch::class)->name('lands.search');
    Route::get('/lands/{id}', LandDetail::class)->name('lands.detail');
    
    // Equipment routes
    Route::get('/equipment/search', EquipmentSearch::class)->name('equipment.search');
    Route::get('/equipment/{id}', EquipmentDetail::class)->name('equipment.detail');
    
    // Announcement routes
    Route::get('/announcements/create', CreateAnnouncement::class)->name('announcements.create');
    Route::get('/my-announcements', MyAnnouncementsManagement::class)->name('announcements.manage');
    
    // Message routes
    Route::get('/messages/inbox', MessagesInbox::class)->name('messages.inbox');
    Route::get('/messages/sent', MessagesSent::class)->name('messages.sent');
    
    // Transaction routes
    Route::get('/transactions', TransactionManagement::class)->name('transactions');
    
    // Order routes
    Route::get('/orders', [OrderManagement::class, 'render'])->name('orders.index');
    Route::get('/orders/{id}', [OrderManagement::class, 'mount'])->name('orders.detail');
    
    // Favorites routes
    Route::get('/favorites', FavoritesManagement::class)->name('favorites');
    
    // Notifications routes
    Route::get('/notifications', NotificationsComponent::class)->name('notifications');
    
    // User profile management
    Route::get('/profile', ProfileManagement::class)->name('profile');
    
    // Client-specific routes
    Route::middleware(['client'])->group(function () {
        Route::get('/client/dashboard', ClientDashboard::class)->name('client.dashboard');
        Route::get('/my-lands', MyLandsManagement::class)->name('lands.manage');
    });
    
    // Supplier-specific routes
    Route::middleware(['supplier'])->group(function () {
        Route::get('/supplier/dashboard', SupplierDashboard::class)->name('supplier.dashboard');
        Route::get('/equipment/create', [EquipmentManagement::class, 'render'])->name('equipment.create');
        Route::get('/equipment/{id}/edit', [EquipmentManagement::class, 'mount'])->name('equipment.edit');
        Route::get('/my-equipment', EquipmentManagement::class)->name('equipment.manage');
    });
    
    // Admin routes
    Route::prefix('admin')->middleware(['admin'])->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/reports', Reports::class)->name('admin.reports');
        Route::get('/settings', SystemSettings::class)->name('admin.settings');
        Route::get('/users', UserManagement::class)->name('admin.users');
        Route::get('/announcements', PendingAnnouncements::class)->name('admin.announcements');
        Route::get('/transactions', TransactionManagement::class)->name('admin.transactions');
        Route::get('/commissions', CommissionManagement::class)->name('admin.commissions');
    });
});