<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;



// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return redirect('/login');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit']);
    Route::post('/products/{id}/update', [ProductController::class, 'update']);
    Route::post('/products/{id}/delete', [ProductController::class, 'delete']);


    //route for customers
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/create', [CustomerController::class, 'create']);
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit']);
    Route::post('/customers/{id}/update', [CustomerController::class, 'update']);
    Route::post('/customers/{id}/delete', [CustomerController::class, 'delete']);


    //route for invoice creation

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/invoice/export-csv', [InvoiceController::class, 'exportCsv'])->name('invoice.export.csv');
    Route::get('/invoice/{id}/edit', [InvoiceController::class, 'edit'])->name('invoice.edit');
    Route::post('/invoice/{id}/update', [InvoiceController::class, 'update'])->name('invoice.update');
    Route::post('/invoice/{id}/delete', [InvoiceController::class, 'delete'])->name('invoice.delete');


    Route::get('/invoice/create', [InvoiceController::class, 'create']);
    Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{id}/pdf', [InvoiceController::class, 'downloadPDF'])->name('invoice.pdf');
    Route::get('/invoice/{id}/receipt', [InvoiceController::class, 'receipt'])->name('invoice.receipt');
    Route::get(
        '/sales-report',
        [InvoiceController::class, 'salesReport']
    )
        ->name('sales.report');
    Route::get('/customer-ledger', [InvoiceController::class, 'customerLedger'])
        ->name('customer.ledger');

    // Route::get('/dashboard', [DashboardController::class, 'index'])
    //     ->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');


    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::post('/suppliers/{id}/delete', [SupplierController::class, 'delete'])->name('suppliers.delete');

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{id}', [PurchaseController::class, 'show'])->name('purchases.show');

    Route::get('/purchase-report', [PurchaseController::class, 'purchaseReport'])
        ->name('purchase.report');

    Route::get('/purchase-report/export', [PurchaseController::class, 'exportPurchaseReport'])
        ->name('purchase.report.export');

    Route::get('/stock-report', [ProductController::class, 'stockReport'])
        ->name('stock.report');

    Route::get('/stock-report/export', [ProductController::class, 'exportStockReport'])
        ->name('stock.report.export');

    //routes for user permission management

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create');

    Route::post('/users/store', [UserController::class, 'store'])
        ->name('users.store');

    Route::get('/users/{id}/edit', [UserController::class, 'edit'])
        ->name('users.edit');

    Route::post('/users/{id}/update', [UserController::class, 'update'])
        ->name('users.update');

    Route::post('/users/{id}/delete', [UserController::class, 'delete'])
        ->name('users.delete');

});





require __DIR__ . '/auth.php';
