<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\BranchController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\AdminCabang\DashboardController as AdminDashboard;
use App\Http\Controllers\AdminCabang\MenuController;
use App\Http\Controllers\AdminCabang\CategoryController;
use App\Http\Controllers\AdminCabang\TableController;
use App\Http\Controllers\AdminCabang\UserController as AdminUserController;
use App\Http\Controllers\AdminCabang\ReportController;
use App\Http\Controllers\Kasir\OrderController;
use App\Http\Controllers\Koki\KitchenController;
use App\Http\Controllers\CustomerOrderController;

// Landing Page
Route::get('/', fn() => view('landing', [
    'branches' => \App\Models\Branch::with('tables')->get()
]))->name('landing');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Customer Order Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/pesan', [CustomerOrderController::class, 'index'])->name('pesan');
    Route::get('/order', [CustomerOrderController::class, 'index'])->name('order.qr');
    Route::post('/pesan', [CustomerOrderController::class, 'store'])->name('pesan.store');
});

// Super Admin Routes
Route::prefix('superadmin')->middleware(['auth', 'role:superadmin'])->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('branches', BranchController::class);
    Route::resource('users', SuperAdminUserController::class);
});

// Admin Cabang Routes
Route::prefix('admin')->middleware(['auth', 'role:admin_cabang'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('menus', MenuController::class);
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
    Route::resource('tables', TableController::class)->except(['show', 'create', 'edit']);
    Route::post('tables/{table}/regenerate-qr', [TableController::class, 'regenerateQr'])->name('tables.regenerate-qr');
    Route::resource('users', AdminUserController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

// Kasir Routes
Route::prefix('kasir')->middleware(['auth', 'role:kasir'])->name('kasir.')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::patch('/orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
});

// Koki Routes
Route::prefix('dapur')->middleware(['auth', 'role:koki'])->name('koki.')->group(function () {
    Route::get('/', [KitchenController::class, 'index'])->name('kitchen');
    Route::patch('/orders/{order}/status', [KitchenController::class, 'updateStatus'])->name('orders.update-status');
});

