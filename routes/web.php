<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return redirect('/login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


// Route::get('/dashboard', function () {

//     return view('dashboard', [
//         'totalProducts' => \App\Models\Product::count(),
//         'totalCustomers' => \App\Models\Customer::count(),
//         'totalInvoices' => \App\Models\Invoice::count(),

//         'recentInvoices' => \App\Models\Invoice::with('customer')
//             ->latest()
//             ->take(5)
//             ->get()
//     ]);

// })->middleware(['auth'])->name('dashboard');
// Route::get('/dashboard', function () {

//     $userId = Auth::id();

//     return view('dashboard', [
//         'totalProducts' => \App\Models\Product::where('user_id', $userId)->count(),
//         'totalCustomers' => \App\Models\Customer::where('user_id', $userId)->count(),
//         'totalInvoices' => \App\Models\Invoice::where('user_id', $userId)->count(),

//         'recentInvoices' => \App\Models\Invoice::where('user_id', $userId)
//             ->with('customer')
//             ->latest()
//             ->take(5)
//             ->get()
//     ]);

// })->middleware(['auth'])->name('dashboard');
// Route::get('/dashboard', function () {

//     $userId = Auth::id();

//     $totalSales = \App\Models\Invoice::where('user_id', $userId)
//         ->sum('final_amount');

//     $todaySales = \App\Models\Invoice::where('user_id', $userId)
//         ->whereDate('created_at', today())
//         ->sum('final_amount');

//     $totalProducts = \App\Models\Product::where('user_id', $userId)->count();

//     $totalCustomers = \App\Models\Customer::where('user_id', $userId)->count();

//     $totalInvoices = \App\Models\Invoice::where('user_id', $userId)->count();

//     $lowStockProducts = \App\Models\Product::where('user_id', $userId)
//         ->where('stock_quantity', '<=', 5)
//         ->count();

//     $latestInvoices = \App\Models\Invoice::where('user_id', $userId)
//         ->with('customer')
//         ->latest()
//         ->take(5)
//         ->get();

//     $last7Days = collect();

//     for ($i = 6; $i >= 0; $i--) {
//         $date = now()->subDays($i)->format('Y-m-d');

//         $last7Days->push([
//             'date' => now()->subDays($i)->format('d M'),
//             'sales' => \App\Models\Invoice::where('user_id', $userId)
//                 ->whereDate('created_at', $date)
//                 ->sum('final_amount'),
//         ]);
//     }

//     $salesChartLabels = $last7Days->pluck('date');
//     $salesChartData = $last7Days->pluck('sales');

//     return view('dashboard', compact(
//         'totalSales',
//         'todaySales',
//         'totalProducts',
//         'totalCustomers',
//         'totalInvoices',
//         'lowStockProducts',
//         'latestInvoices',
//         'salesChartLabels',
//         'salesChartData'
//     ));

// })->middleware(['auth'])->name('dashboard');

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

});





require __DIR__ . '/auth.php';
