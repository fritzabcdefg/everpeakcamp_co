<?php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// Home Route - Product listing with search
Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/register', [UserController::class, 'createRegister'])->name('register');
Route::post('/register', [UserController::class, 'storeRegister']);
Route::get('/login', [UserController::class, 'createLogin'])->name('login');
Route::post('/login', [UserController::class, 'storeLogin']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Email Verification Routes
Route::get('/email/verify/{token}', [UserController::class, 'verifyEmail'])->name('email.verify');
Route::post('/email/resend', [UserController::class, 'resendVerification'])->name('email.resend');

// Dashboard - Admin only
Route::middleware(['auth', 'admin'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Admin only routes (MUST come before generic {product} route)
Route::middleware(['auth', 'admin'])->group(function () {
    // Product Management
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/datatable', [ProductController::class, 'datatable'])->name('products.datatable');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products-image/{imageId}', [ProductController::class, 'deleteImage'])->name('products.deleteImage');
    Route::get('/products/import/form', [ProductController::class, 'importForm'])->name('products.importForm');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');

    // Category Management
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Order Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::put('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Review Management
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
});

// Public product routes (show only) - AFTER create/edit routes above
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Shop route - All products page
Route::get('/shop', [ShopController::class, 'show'])->name('shop.show');

// Public category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Protected routes (Authenticated users)
Route::middleware('auth')->group(function () {
    // Customer Cart & Orders
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout (confirm and process order) for customers
    Route::get('/checkout', [OrderController::class, 'checkoutForm'])->name('checkout.index');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');

    // Customer orders (view only)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // User Profile (generic) – used by admin and other links
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // current user profile shortcuts
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/index', [UserController::class, 'showProfile'])->name('profile.index');
    Route::get('/profile/create', [UserController::class, 'createProfile'])->name('profile.create');
    Route::post('/profile', [UserController::class, 'storeProfile'])->name('profile.store');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Review routes
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/products/{product}/reviews', [ReviewController::class, 'productReviews'])->name('product.reviews');
});
