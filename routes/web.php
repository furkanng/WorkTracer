<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Technician\TaskController as TechnicianTaskController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Technician\MessageController as TechnicianMessageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SecretaryController;
use App\Http\Controllers\Admin\PriceListController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->isSecretary()) {
            return redirect()->route('secretary.dashboard');
        }
        return redirect()->route('technician.dashboard');
    }
    return redirect()->route('login');
});

// Auth routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('customers', CustomerController::class);
    Route::post('/customers/{customer}/transactions', [CustomerController::class, 'storeTransaction'])
        ->name('customers.transactions.store');
    Route::resource('tasks', TaskController::class);
    Route::resource('invoices', InvoiceController::class)->only(['show']);
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::resource('users', UserController::class)->except(['show', 'destroy']);
    Route::resource('secretaries', SecretaryController::class)->except(['show', 'destroy']);
    Route::resource('prices', PriceListController::class)->except(['show', 'destroy']);
    Route::resource('brands', BrandController::class)->except(['show', 'destroy']);
});

// Technician routes
Route::middleware(['auth', 'role:technician'])->prefix('technician')->name('technician.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Technician\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/tasks', [TechnicianTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [TechnicianTaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TechnicianTaskController::class, 'edit'])->name('tasks.edit');
    Route::post('/customers/{customer}/transactions', [TechnicianTaskController::class, 'storeTransaction'])
        ->name('customers.transactions.store');
    Route::patch('/tasks/{task}/status', [TechnicianTaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::get('/messages', [TechnicianMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [TechnicianMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [TechnicianMessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{message}/read', [TechnicianMessageController::class, 'markAsRead'])->name('messages.read');
});

// Sekreter routes
Route::middleware(['auth', 'role:secretary'])->prefix('secretary')->name('secretary.')->group(function () {
    Route::get('/dashboard', function () {
        return view('secretary.dashboard');
    })->name('dashboard');
    
    // Müşteri rotaları
    Route::resource('customers', \App\Http\Controllers\Secretary\CustomerController::class);
    Route::post('/customers/{customer}/transactions', [\App\Http\Controllers\Secretary\CustomerController::class, 'storeTransaction'])
        ->name('customers.transactions.store');

    // Task rotaları - resource olarak tanımla
    Route::resource('tasks', \App\Http\Controllers\Secretary\TaskController::class);

    // Mesaj rotaları
    Route::get('/messages', [\App\Http\Controllers\Secretary\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [\App\Http\Controllers\Secretary\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [\App\Http\Controllers\Secretary\MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{message}/read', [\App\Http\Controllers\Secretary\MessageController::class, 'markAsRead'])->name('messages.read');
});
