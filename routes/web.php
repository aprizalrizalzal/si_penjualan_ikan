<?php

use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('welcome');;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['role:admin'])->group(function () {

    Route::get('/products', [ProductController::class, 'show'])->name('show.products');
    Route::post('/product', [ProductController::class, 'store'])->name('store.product');
    Route::put('/product', [ProductController::class, 'update'])->name('update.product');
    Route::delete('/product', [ProductController::class, 'destroy'])->name('destroy.product');

    Route::get('/categories', [CategoryController::class, 'show'])->name('show.categories');
    Route::post('/category', [CategoryController::class, 'store'])->name('store.category');
    Route::put('/category', [CategoryController::class, 'update'])->name('update.category');
    Route::delete('/category', [CategoryController::class, 'destroy'])->name('destroy.category');

    Route::get('/users', [UserController::class, 'show'])->name('show.users');
    Route::delete('/user', [UserController::class, 'destroy'])->name('destroy.user');

    Route::post('/user/assign-role', [RoleController::class, 'assignRole'])->name('assign.roles');
    Route::delete('/user/remove-role', [RoleController::class, 'removeRole'])->name('remove.role');
});

Route::middleware(['role:user'])->group(function () {});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
