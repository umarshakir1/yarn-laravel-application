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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('products', ProductController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('expense-categories', ExpenseCategoryController::class);

    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit_loss');

    // Account Management
    Route::resource('accounts', AccountController::class);

    // Double-Entry Transfers
    Route::resource('transfers', TransferController::class);

    // Ledger Reports
    Route::prefix('ledgers')->name('ledgers.')->group(function () {
        Route::get('/customers', [LedgerController::class, 'customerIndex'])->name('customers.index');
        Route::get('/customers/{client}', [LedgerController::class, 'customerShow'])->name('customers.show');
        Route::get('/customers/{client}/pdf', [LedgerController::class, 'customerPdf'])->name('customers.pdf');

        Route::get('/suppliers', [LedgerController::class, 'supplierIndex'])->name('suppliers.index');
        Route::get('/suppliers/{client}', [LedgerController::class, 'supplierShow'])->name('suppliers.show');
        Route::get('/suppliers/{client}/pdf', [LedgerController::class, 'supplierPdf'])->name('suppliers.pdf');
    });
});

require __DIR__.'/auth.php';
