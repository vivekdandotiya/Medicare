<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Models\Medicine;
use App\Models\Category;
use App\Models\Brand;

// Public homepage showing the storefront
Route::get('/', function () {
    $categories = Category::where('status', true)->get();
    $brands = Brand::where('status', true)->get();
    $medicines = Medicine::with(['category', 'brand'])->where('status', true)->latest()->take(8)->get();
    return view('welcome', compact('categories', 'brands', 'medicines'));
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Medicines Resource
    Route::resource('medicines', MedicineController::class);

    // Categories Resource
    Route::resource('categories', CategoryController::class);

    // Brands Resource
    Route::resource('brands', BrandController::class);

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{medicine}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout & Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    // Checkout & Orders
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

    // Prescriptions
    Route::get('/prescriptions', [App\Http\Controllers\PrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::post('/prescriptions', [App\Http\Controllers\PrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::patch('/prescriptions/{prescription}', [App\Http\Controllers\PrescriptionController::class, 'update'])->name('prescriptions.update');
    Route::delete('/prescriptions/{prescription}', [App\Http\Controllers\PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
});

require __DIR__.'/auth.php';