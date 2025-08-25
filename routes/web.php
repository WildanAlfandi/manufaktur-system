<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Product routes
    Route::resource('products', ProductController::class);

    // Transaction routes
    Route::get('/transactions/stock-in', [TransactionController::class, 'stockIn'])->name('transactions.stock-in');
    Route::post('/transactions/stock-in', [TransactionController::class, 'storeStockIn'])->name('transactions.stock-in.store');
    Route::get('/transactions/stock-out', [TransactionController::class, 'stockOut'])->name('transactions.stock-out');
    Route::post('/transactions/stock-out', [TransactionController::class, 'storeStockOut'])->name('transactions.stock-out.store');
    Route::get('/transactions/history', [TransactionController::class, 'history'])->name('transactions.history');

    // Hanya admin yang bisa akses ini
    Route::middleware('admin')->group(function () {
        // Master Data
        Route::resource('suppliers', SupplierController::class);
        Route::resource('customers', CustomerController::class);

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/stock', [ReportController::class, 'exportStock'])->name('reports.export.stock');
        Route::get('/reports/export/transactions', [ReportController::class, 'exportTransactions'])->name('reports.export.transactions');

        // User Management
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // Categories
        Route::resource('categories', CategoryController::class);

        // Global Search
        Route::get('/search', [SearchController::class, 'globalSearch'])->name('search.global');

        // Notification Settings
        Route::get('/settings/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
        Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
        Route::post('/settings/notifications/test', [SettingsController::class, 'testEmail'])->name('settings.notifications.test');
        Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    });
});

require __DIR__.'/auth.php';
