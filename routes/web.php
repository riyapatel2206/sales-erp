<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';



Route::middleware(['auth', 'verified'])->group(function () {
    


    // Products and dashboard routes - Admin only access
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', ProductController::class);
    });

    // Sales  routes - admin and salesperson both access
    Route::middleware(['role:admin,salesperson'])->group(function () {
       
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [SalesController::class, 'index'])->name('list');
            Route::get('/create', [SalesController::class, 'create'])->name('create');
            Route::post('/', [SalesController::class, 'store'])->name('store');
            Route::get('/{salesOrder}', [SalesController::class, 'show'])->name('show');
            Route::patch('/{salesOrder}/confirm', [SalesController::class, 'confirm'])->name('confirm');
            Route::get('/{salesOrder}/pdf', [SalesController::class, 'downloadPdf'])->name('pdf');
        });
    });
});

