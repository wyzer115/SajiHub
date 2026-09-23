<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\BranchController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\ReportController as SuperAdminReportController;
use App\Http\Controllers\AdminCabang\DashboardController as AdminDashboard;
use App\Http\Controllers\AdminCabang\MenuController;
use App\Http\Controllers\AdminCabang\CategoryController;
use App\Http\Controllers\AdminCabang\TableController;
use App\Http\Controllers\AdminCabang\StaffController;
use App\Http\Controllers\AdminCabang\UserController as AdminUserController;
use App\Http\Controllers\AdminCabang\ReportController as AdminReportController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\Supervisor\InventoryController;
use App\Http\Controllers\Kasir\OrderController;
use App\Http\Controllers\Koki\KitchenController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\PublicMenuController;

// Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match ($user->role) {
            'superadmin'   => redirect()->route('superadmin.dashboard'),
            'admin_cabang' => redirect()->route('admin.dashboard'),
            'owner'        => redirect()->route('owner.dashboard'),
            'supervisor'   => redirect()->route('supervisor.inventory.index'),
            'kasir'        => redirect()->route('kasir.orders.index'),
            'dapur', 'koki'=> redirect()->route('koki.kitchen'),
            default        => view('landing', ['branches' => \App\Models\Branch::with('tables')->get()]),
        };
    }
    return view('landing', [
        'branches' => \App\Models\Branch::with('tables')->get()
    ]);
})->name('landing');

// Public Menu Catalog (Lihat Menu Saja, Tanpa Form Pesan)
Route::get('/menu', [PublicMenuController::class, 'index'])->name('menu.catalog');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', fn() => redirect()->route('login'))->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// Customer Order / QR Scan Routes (Public - No Login Required)
Route::get('/order', [CustomerOrderController::class, 'index'])->name('order.qr');
Route::get('/pesan', [CustomerOrderController::class, 'index'])->name('pesan');
Route::post('/pesan', [CustomerOrderController::class, 'store'])->name('pesan.store');
Route::get('/pesan/{order}/receipt', [CustomerOrderController::class, 'showReceipt'])->name('pesan.receipt');
Route::get('/pesan/{order}/status', [CustomerOrderController::class, 'checkStatus'])->name('pesan.status');


// Super Admin Routes
Route::prefix('superadmin')->middleware(['auth', 'role:superadmin'])->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
    Route::patch('/branches/{branch}/toggle-status', [BranchController::class, 'toggleStatus'])->name('branches.toggle-status');
    Route::patch('/branches/{branch}/status', [BranchController::class, 'updateStatus'])->name('branches.status');
    Route::post('/branches/{branch}/impersonate', [App\Http\Controllers\SuperAdmin\ImpersonateController::class, 'start'])->name('branches.impersonate');
    Route::resource('branches', BranchController::class);
    Route::resource('users', SuperAdminUserController::class);
    Route::get('/reports', [SuperAdminReportController::class, 'index'])->name('reports');
});

// Impersonate Leave Route
Route::post('/superadmin/impersonate/leave', [App\Http\Controllers\SuperAdmin\ImpersonateController::class, 'leave'])
    ->name('superadmin.impersonate.leave')
    ->middleware('auth');

// Admin Cabang Routes
Route::prefix('admin')->middleware(['auth', 'role:admin_cabang'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('menus', MenuController::class);
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
    Route::resource('tables', TableController::class)->except(['show', 'create', 'edit']);
    Route::post('tables/{table}/regenerate-qr', [TableController::class, 'regenerateQr'])->name('tables.regenerate-qr');
    Route::get('tables/{table}/qr', [TableController::class, 'showQr'])->name('tables.qr');
    Route::resource('staff', StaffController::class);
    Route::resource('users', AdminUserController::class);
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
    Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');
});

// Owner Routes
Route::prefix('owner')->middleware(['auth', 'role:owner'])->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index'])->name('dashboard');
    Route::get('/reports', [OwnerController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [OwnerController::class, 'exportReports'])->name('reports.export');
    Route::get('/inventory', [OwnerController::class, 'inventory'])->name('inventory');
});

// Supervisor Routes
Route::prefix('supervisor')->middleware(['auth', 'role:supervisor'])->name('supervisor.')->group(function () {
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

    // Redirect old stock-opname to unified inventory
    Route::get('/stock-opname', fn() => redirect()->route('supervisor.inventory.index'))->name('opname.index');
    Route::post('/stock-opname', [InventoryController::class, 'storeOpname'])->name('opname.store');
    Route::resource('expenses', \App\Http\Controllers\Supervisor\SupervisorExpenseController::class);
});

// Kasir Routes
Route::prefix('kasir')->middleware(['auth', 'role:kasir'])->name('kasir.')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/transactions', [OrderController::class, 'transactions'])->name('transactions');
    Route::get('/reports', [OrderController::class, 'financialReport'])->name('reports');
    Route::get('/tables', [\App\Http\Controllers\AdminCabang\TableController::class, 'index'])->name('tables.index');
    Route::post('/tables', [\App\Http\Controllers\AdminCabang\TableController::class, 'store'])->name('tables.store');
    Route::put('/tables/{table}', [\App\Http\Controllers\AdminCabang\TableController::class, 'update'])->name('tables.update');
    Route::delete('/tables/{table}', [\App\Http\Controllers\AdminCabang\TableController::class, 'destroy'])->name('tables.destroy');
    Route::post('/tables/{table}/regenerate-qr', [\App\Http\Controllers\AdminCabang\TableController::class, 'regenerateQr'])->name('tables.regenerate-qr');
    
    // Restricted routes when branch is closed/maintenance
    Route::middleware(['branch.status'])->group(function () {
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    });

    Route::get('/orders/scan', [OrderController::class, 'scan'])->name('orders.scan');
    Route::post('/orders/lookup', [OrderController::class, 'lookupOrder'])->name('orders.lookup');
    Route::post('/orders/{order}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');

    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::patch('/orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/orders/{order}/process-payment', [OrderController::class, 'processPayment'])->name('orders.process-payment');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
});

// Dapur / Kitchen Routes
Route::prefix('dapur')->middleware(['auth', 'role:dapur,koki'])->name('koki.')->group(function () {
    Route::get('/', [KitchenController::class, 'index'])->name('kitchen');
    Route::get('/menu-status', [KitchenController::class, 'menuStatus'])->name('menu-status');
    Route::patch('/menu-status/{menu}', [KitchenController::class, 'toggleMenuStatus'])->name('menu-status.toggle');
    Route::get('/history', [KitchenController::class, 'history'])->name('history');
    Route::patch('/orders/{order}/status', [KitchenController::class, 'updateStatus'])->name('orders.update-status');
});