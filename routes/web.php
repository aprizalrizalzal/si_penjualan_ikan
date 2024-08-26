<?php

use App\Http\Controllers\Banner\BannerController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Customer\CustomerImageController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\PaymentImageController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Seller\SellerImageController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\User\UserController;
use App\Models\Banner\Banner;
use App\Models\Order\Order;
use App\Models\Payment\Payment;
use App\Models\Product\Product;
use App\Models\Seller\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'banners' => Banner::all(),
        'products' => Product::with('category', 'seller', 'productImages')->get(),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('welcome');;

Route::get('/dashboard', function () {
    $userId = Auth::id();

    // Inisialisasi variabel dengan nilai default
    $sellers = [];
    $products = [];
    $users = [];
    $orders = [];
    $payments = [];

    if (Auth::user()->hasRole('admin')) {
        // Admin dapat melihat semua pembayaran
        $sellers = Seller::get();
        $products = Product::get();
        $users = User::with('roles')->get();
        $orders = Order::get();
        $payments = Payment::with('order', 'order.orderItems', 'order.orderItems.product', 'order.user', 'order.user.customer')->get();
    } else {
        // User hanya dapat melihat pembayaran mereka sendiri
        $payments = Payment::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('order', 'order.orderItems', 'order.orderItems.product', 'order.user', 'order.user.customer')->get();
    }

    return Inertia::render('Dashboard', [
        'sellers' => $sellers,
        'products' => $products,
        'users' => $users,
        'orders' => $orders,
        'payments' => $payments,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::post('/banner/image', [BannerController::class, 'store'])->name('store.banner.image');
    Route::delete('/banner/image', [BannerController::class, 'destroy'])->name('destroy.banner.image');

    Route::post('/seller/image', [SellerImageController::class, 'store'])->name('store.seller.image');
    Route::delete('/seller/image', [SellerImageController::class, 'destroy'])->name('destroy.seller.image');

    Route::get('/products', [ProductController::class, 'show'])->name('show.products');
    Route::post('/product', [ProductController::class, 'store'])->name('store.product');
    Route::put('/product', [ProductController::class, 'update'])->name('update.product');
    Route::delete('/product', [ProductController::class, 'destroy'])->name('destroy.product');

    Route::post('/product/image', [ProductImageController::class, 'store'])->name('store.product.image');
    Route::delete('/product/image', [ProductImageController::class, 'destroy'])->name('destroy.product.image');

    Route::get('/categories', [CategoryController::class, 'show'])->name('show.categories');
    Route::post('/category', [CategoryController::class, 'store'])->name('store.category');
    Route::put('/category', [CategoryController::class, 'update'])->name('update.category');
    Route::delete('/category', [CategoryController::class, 'destroy'])->name('destroy.category');

    Route::get('/users', [UserController::class, 'show'])->name('show.users');
    Route::delete('/user', [UserController::class, 'destroy'])->name('destroy.user');

    Route::post('/user/assign-role', [RoleController::class, 'assignRole'])->name('assign.roles');
    Route::delete('/user/remove-role', [RoleController::class, 'removeRole'])->name('remove.role');

    Route::get('/settings', [SettingController::class, 'show'])->name('show.settings');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/customer/image', [CustomerImageController::class, 'store'])->name('store.customer.image');
    Route::delete('/customer/image', [CustomerImageController::class, 'destroy'])->name('destroy.customer.image');

    Route::post('/product/carts', [ProductController::class, 'store_cart'])->name('store.product.carts');

    Route::get('/carts', [CartController::class, 'show'])->name('show.carts');
    Route::post('/cart/orders', [CartController::class, 'store_order'])->name('store.cart.orders');
    Route::put('/cart', [CartController::class, 'update'])->name('update.cart');
    Route::delete('/cart', [CartController::class, 'destroy'])->name('destroy.cart');

    Route::get('/orders', [OrderController::class, 'show'])->name('show.orders');
    Route::post('/order/payments', [PaymentController::class, 'store_payment'])->name('store.order.payment');
    Route::delete('/order', [OrderController::class, 'destroy'])->name('destroy.order');

    Route::get('/payments', [PaymentController::class, 'show'])->name('show.payments');
    Route::put('/payment', [PaymentController::class, 'update'])->name('update.payment');
    Route::delete('/payment', [PaymentController::class, 'destroy'])->name('destroy.payment');

    Route::post('/payment/image', [PaymentImageController::class, 'store'])->name('store.payment.image');
    Route::delete('/payment/image', [PaymentImageController::class, 'destroy'])->name('destroy.payment.image');
});

require __DIR__ . '/auth.php';
