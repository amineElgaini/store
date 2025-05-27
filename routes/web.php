<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    // Route::resource('products.variants', ProductVariantController::class)
    // ->shallow()->except(['index', 'create', 'show']);
    Route::resource('packages', PackageController::class);
    Route::resource('orders', OrderController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Edit all variants of a product (show variants + create new + edit existing)
    Route::get('products/{product}/variants/edit', [ProductVariantController::class, 'edit'])->name('products.variants.edit');

    // Store a new variant for a product
    Route::post('products/{product}/variants', [ProductVariantController::class, 'store'])->name('products.variants.store');

    // Update an existing variant
    Route::put('variants/{variant}', [ProductVariantController::class, 'update'])->name('variants.update');

    // Delete a variant
    Route::delete('variants/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
