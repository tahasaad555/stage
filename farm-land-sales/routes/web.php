<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\LandSearch;
use App\Http\Livewire\EquipmentSearch;
use App\Http\Livewire\LandDetail;
use App\Http\Livewire\CreateAnnouncement;
use App\Http\Livewire\MessagesInbox;
use App\Http\Livewire\TransactionManagement;
use App\Http\Livewire\SupplierDashboard;
use App\Http\Livewire\EquipmentManagement;
use App\Http\Livewire\OrderManagement;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// Route::get('/', Home::class)->name('home');
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    // Land routes
    Route::get('/lands', LandSearch::class)->name('lands.search');
    Route::get('/lands/{id}', LandDetail::class)->name('lands.detail');
    
    // Equipment routes
    Route::get('/equipment', EquipmentSearch::class)->name('equipment.search');
    Route::get('/equipment/create', EquipmentManagement::class)->name('equipment.create');
    Route::get('/equipment/{id}/edit', EquipmentManagement::class)->name('equipment.edit');
    Route::get('/my-equipment', EquipmentManagement::class)->name('equipment.manage');
    
    // Announcement routes
    Route::get('/announcements/create', CreateAnnouncement::class)->name('announcements.create');
    
    // Message routes
    Route::get('/messages', MessagesInbox::class)->name('messages.inbox');
    
    // Transaction routes
    Route::get('/transactions', TransactionManagement::class)->name('transactions');
    
    // Order routes
    Route::get('/orders', OrderManagement::class)->name('orders.index');
    Route::get('/orders/{id}', OrderManagement::class)->name('orders.detail');
    
    // Supplier routes
    Route::get('/supplier/dashboard', SupplierDashboard::class)->name('supplier.dashboard');
    
    // Favorites routes
Route::get('/favorites', App\Http\Livewire\FavoritesManagement::class)->name('favorites');

// Notifications routes
Route::get('/notifications', App\Http\Livewire\NotificationsComponent::class)->name('notifications');

Route::get('/equipment/{id}', App\Http\Livewire\EquipmentDetail::class)->name('equipment.detail');

Route::get('/profile', App\Http\Livewire\ProfileManagement::class)->name('profile');

// Admin routes with middleware applied inline
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/reports', App\Http\Livewire\Admin\Reports::class)->name('admin.reports');
    Route::get('/settings', App\Http\Livewire\Admin\SystemSettings::class)->name('admin.settings');
    Route::get('/admin/users', App\Http\Livewire\Admin\UserManagement::class)->name('admin.users');
});


});

require __DIR__.'/auth.php';