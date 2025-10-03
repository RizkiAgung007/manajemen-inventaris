<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk produk
    Route::middleware(['role:superadmin,admin'])->prefix(('dashboard'))->group(function () {
        Route::resource('products', ProductController::class);
    });

    // Route untuk manajemen user
    Route::middleware(['role:superadmin'])->prefix(('dashboard'))->group(function () {
        Route::resource('users', UserController::class);
    });

    
});

require __DIR__.'/auth.php';
