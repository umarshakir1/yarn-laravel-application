<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Staff Management — Admin only
    Route::middleware('role:Admin')->group(function () {
        Route::resource('staff', StaffController::class)->except(['show']);
    });

    // Section-protected routes
    Route::middleware('section:view products')->group(function () {
        Route::resource('products', ProductController::class);
    });

    Route::middleware('section:view clients')->group(function () {
        Route::resource('clients', ClientController::class);

        Route::prefix('ledgers')->name('ledgers.')->group(function () {
            Route::get('/customers', [LedgerController::class, 'customerIndex'])->name('customers.index');
            Route::get('/customers/{client}', [LedgerController::class, 'customerShow'])->name('customers.show');
            Route::get('/customers/{client}/pdf', [LedgerController::class, 'customerPdf'])->name('customers.pdf');

            Route::get('/suppliers', [LedgerController::class, 'supplierIndex'])->name('suppliers.index');
            Route::get('/suppliers/{client}', [LedgerController::class, 'supplierShow'])->name('suppliers.show');
            Route::get('/suppliers/{client}/pdf', [LedgerController::class, 'supplierPdf'])->name('suppliers.pdf');
        });
    });

    Route::middleware('section:view purchases')->group(function () {
        Route::resource('purchases', PurchaseController::class);
    });

    Route::middleware('section:view sales')->group(function () {
        Route::resource('sales', SaleController::class);
    });

    Route::middleware('section:view services')->group(function () {
        Route::resource('services', ServiceController::class);
    });

    Route::middleware('section:view expenses')->group(function () {
        Route::resource('expenses', ExpenseController::class);
        Route::resource('expense-categories', ExpenseCategoryController::class);
    });

    Route::middleware('section:view accounts')->group(function () {
        Route::resource('accounts', AccountController::class);
    });

    Route::middleware('section:view transfers')->group(function () {
        Route::resource('transfers', TransferController::class);
    });

    Route::middleware('role:Admin')->group(function () {
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/sales/pdf', [ReportController::class, 'salesPdf'])->name('reports.sales.pdf');
        Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('/reports/inventory/pdf', [ReportController::class, 'inventoryPdf'])->name('reports.inventory.pdf');
        Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit_loss');
        Route::get('/reports/profit-loss/pdf', [ReportController::class, 'profitLossPdf'])->name('reports.profit_loss.pdf');
    });
});

require __DIR__.'/auth.php';
