<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});


route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');



route::middleware('auth')->group(function () {


    // Product
    route::prefix('products')->name('product.')->group(function () {
        route::get('/', [ProductController::class, 'index'])->name('index');
        route::get('/create', [ProductController::class, 'create'])->name('create');
        route::post('/store', [ProductController::class, 'store'])->name('store');
        route::get('/show/{product}', [ProductController::class, 'show'])->name('show');
        route::get('/edit/{product}', [ProductController::class, 'edit'])->name('edit');
        route::put('/update/{product}', [ProductController::class, 'update'])->name('update');
        route::delete('/destroy/{product}', [ProductController::class, 'destroy'])->name('destroy');

        Route::get('/low-stock-products', [ProductController::class, 'lowStock'])->name('lowStock');

        Route::get('/check-code', [ProductController::class, 'checkCode'])->name('checkCode');
    });
    Route::get('/product/{id}/stock', [ProductController::class, 'getStockQuantity']);


    // Category
    route::prefix('categories')->name('category.')->group(function () {
        route::get('/', [CategoryController::class, 'index'])->name('index');
        route::get('/create', [CategoryController::class, 'create'])->name('create');
        route::post('/store', [CategoryController::class, 'store'])->name('store');
        route::get('/show/{category}', [CategoryController::class, 'show'])->name('show');
        route::get('/edit/{category}', [CategoryController::class, 'edit'])->name('edit');
        route::put('/update/{category}', [CategoryController::class, 'update'])->name('update');
        route::delete('/destroy/{category}', [CategoryController::class, 'destroy'])->name('destroy');

        Route::get('/check-code', [CategoryController::class, 'checkCode'])->name('checkCode');
    });

    // Customer
    route::prefix('customers')->name('customer.')->group(function () {
        route::get('/', [CustomerController::class, 'index'])->name('index');
        route::get('/create', [CustomerController::class, 'create'])->name('create');
        route::post('/store', [CustomerController::class, 'store'])->name('store');
        route::get('/edit/{customer}', [CustomerController::class, 'edit'])->name('edit');
        route::put('/update/{customer}', [CustomerController::class, 'update'])->name('update');
        route::delete('/destroy/{customer}', [CustomerController::class, 'destroy'])->name('destroy');

        Route::post('/check-email', [CustomerController::class, 'checkEmail'])->name('checkEmail');
    });

    // supplier
    route::prefix('suppliers')->name('supplier.')->group(function () {
        route::get('/', [SupplierController::class, 'index'])->name('index');
        route::get('/create', [SupplierController::class, 'create'])->name('create');
        route::post('/store', [SupplierController::class, 'store'])->name('store');
        route::get('/edit/{supplier}', [SupplierController::class, 'edit'])->name('edit');
        route::put('/update/{supplier}', [SupplierController::class, 'update'])->name('update');
        route::delete('/destroy/{supplier}', [SupplierController::class, 'destroy'])->name('destroy');

        Route::post('/check-email', [SupplierController::class, 'checkEmail'])->name('checkEmail');
    });

    // Users
    route::prefix('users')->name('user.')->group(function () {
        route::get('/', [UserController::class, 'index'])->name('index');
        route::get('/create', [UserController::class, 'create'])->name('create');
        route::post('/store', [UserController::class, 'store'])->name('store');
        route::get('/show/{user}', [UserController::class, 'show'])->name('show');
        route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
        route::put('/update/{user}', [UserController::class, 'update'])->name('update');
        route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');

        Route::post('/check-email', [UserController::class, 'checkEmail'])->name('checkEmail');
    });

    // Invoices
    route::prefix('invoices')->name('invoice.')->group(function () {
        route::get('/', [InvoicesController::class, 'index'])->name('index');
        route::get('/create', [InvoicesController::class, 'create'])->name('create');
        route::post('/store', [InvoicesController::class, 'store'])->name('store');
        route::get('/show/{invoices}', [InvoicesController::class, 'show'])->name('show');
        route::get('/edit/{invoices}', [InvoicesController::class, 'edit'])->name('edit');
        route::put('/update/{invoices}', [InvoicesController::class, 'update'])->name('update');
        route::delete('/destroy/{invoices}', [InvoicesController::class, 'destroy'])->name('destroy');

        Route::get('/{invoice}/print', [InvoicesController::class, 'print'])->name('print');

        Route::get('/{invoice}/download', [InvoicesController::class, 'download'])->name('download');
    });


});

// Users Toggle
Route::post('/user/toggle-status', [UserController::class, 'toggleStatus'])->name('user.toggleStatus')->middleware('web');



require __DIR__ . '/auth.php';
