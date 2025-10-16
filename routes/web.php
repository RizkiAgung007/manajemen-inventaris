<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockOpnameReviewController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\StockOutController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])->name('dashboard');

// Semua route di bawah ini memerlukan login
Route::middleware('auth')->group(function () {

    // Route Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk laporan
    Route::prefix('dashboard')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
        Route::get('/my-reports', [ReportController::class, 'myReports'])->name('reports.my');
    });

    // Route untuk manajemen konten & review (Admin & Superadmin)
    Route::middleware('can:manage-content')->prefix('dashboard')->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('purchase-orders', PurchaseOrderController::class);
        Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');

        // Route untuk me-review laporan
        Route::get('stock-opname-reviews', [StockOpnameReviewController::class, 'index'])->name('reviews.index');
        Route::get('stock-opname-reviews/{stockOpname}', [StockOpnameReviewController::class, 'show'])->name('reviews.show');
        Route::post('stock-opname-reviews/{stockOpname}/approve', [StockOpnameReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('stock-opname-reviews/{stockOpname}/reject', [StockOpnameReviewController::class, 'reject'])->name('reviews.reject');

        Route::resource('suppliers', SupplierController::class);

        // Route untuk barang keluar
        Route::get('stock-out', [StockOutController::class, 'index'])->name('stock-out.index');
        Route::get('stock-out/create', [StockOutController::class, 'create'])->name('stock-out.create');
        Route::post('stock-out', [StockOutController::class, 'store'])->name('stock-out.store');
        Route::post('stock-out/{stockMovement}/cancel', [StockOutController::class, 'cancel'])->name('stock-out.cancel');
        Route::delete('stock-out/{stockMovement}', [StockOutController::class, 'destroy'])->name('stock-out.destroy');
    });

    Route::middleware('can:manage-users')->prefix('dashboard')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('logs.index');
    });

    // Route untuk admin men-download PDF dari laporan yang sudah ada
    Route::get('dashboard/stock-opname-reviews/{stockOpname}/pdf', [StockOpnameReviewController::class, 'downloadPdf'])->name('reviews.pdf')->middleware('can:view-report,stockOpname');

});

require __DIR__.'/auth.php';
