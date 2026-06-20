<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Shop\AccountController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\CustomRequestController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\PageController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Storefront (customer-facing)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/category/{category:slug}', [ProductController::class, 'category'])->name('categories.show');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/product/{product:slug}/review', [ProductController::class, 'review'])
    ->middleware('auth')->name('products.review');

// Cart
Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/add', 'add')->name('add');
    Route::patch('/items/{item}', 'update')->name('update');
    Route::delete('/items/{item}', 'remove')->name('remove');
    Route::post('/items/{item}/save-for-later', 'saveForLater')->name('save');
    Route::post('/items/{item}/move-to-cart', 'moveToCart')->name('move');
    Route::post('/coupon', 'applyCoupon')->name('coupon.apply');
    Route::delete('/coupon', 'removeCoupon')->name('coupon.remove');
    Route::get('/count', 'count')->name('count');
});

// Wishlist
Route::controller(WishlistController::class)->prefix('wishlist')->name('wishlist.')->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/toggle/{product}', 'toggle')->name('toggle');
    Route::delete('/{product}', 'remove')->name('remove');
});

// Checkout & order placement (guest checkout allowed)
Route::controller(CheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/place', 'place')->name('place');
});

// Orders (customer)
Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
    Route::get('/track', 'trackForm')->name('track.form');
    Route::post('/track', 'track')->name('track');
    Route::get('/{order:order_number}/confirmation', 'confirmation')->name('confirmation');
    Route::get('/{order:order_number}/whatsapp', 'whatsapp')->name('whatsapp');
    Route::get('/{order:order_number}/invoice', 'invoice')->name('invoice');
    Route::get('/{order:order_number}/pay', 'payForm')->name('pay.form');
    Route::post('/{order:order_number}/pay', 'submitPayment')->name('pay.submit');
});

// Custom crochet requests
Route::controller(CustomRequestController::class)->prefix('custom-order')->name('custom.')->group(function () {
    Route::get('/', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{customRequest:request_number}', 'show')->name('show');
});

// Static pages, contact, newsletter
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::post('/newsletter', [PageController::class, 'newsletter'])->name('newsletter.subscribe');
Route::view('/privacy-policy', 'shop.pages.policy', ['policy' => 'privacy'])->name('policy.privacy');
Route::view('/terms', 'shop.pages.policy', ['policy' => 'terms'])->name('policy.terms');

/*
|--------------------------------------------------------------------------
| Customer account (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [AccountController::class, 'updatePassword'])->name('password.update');

    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{order:order_number}', [AccountController::class, 'showOrder'])->name('orders.show');

    // In-app notifications
    Route::get('/notifications', [\App\Http\Controllers\Shop\NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Shop\NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\Shop\NotificationController::class, 'read'])->name('notifications.read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Shop\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Address book
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}', [AccountController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AccountController::class, 'destroyAddress'])->name('addresses.destroy');
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Email verification
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Admin panel
|--------------------------------------------------------------------------
*/
require __DIR__.'/admin.php';
