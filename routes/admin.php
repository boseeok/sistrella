<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomRequestController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin authentication
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'show'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin panel (staff only)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart/{type}', [DashboardController::class, 'chart'])->name('dashboard.chart');

    // Staff notification feed (available to any admin user)
    Route::controller(\App\Http\Controllers\Admin\NotificationController::class)
        ->prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/read-all', 'readAll')->name('readAll');
            Route::get('/{id}/read', 'read')->name('read');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

    // Orders
    Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->middleware('permission:orders.view')->name('index');
        Route::get('/{order:order_number}', 'show')->middleware('permission:orders.view')->name('show');
        Route::patch('/{order:order_number}/status', 'updateStatus')->middleware('permission:orders.manage')->name('status');
        Route::patch('/{order:order_number}/notes', 'updateNotes')->middleware('permission:orders.manage')->name('notes');
        Route::get('/{order:order_number}/invoice', 'invoice')->middleware('permission:orders.view')->name('invoice');
    });

    // Payments / verification queue
    Route::controller(PaymentController::class)->prefix('payments')->name('payments.')
        ->middleware('permission:payments.manage')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/queue', 'queue')->name('queue');
            Route::post('/{payment}/verify', 'verify')->name('verify');
            Route::post('/{payment}/reject', 'reject')->name('reject');
            Route::post('/order/{order:order_number}/record', 'record')->name('record');
        });

    // Catalogue
    Route::resource('products', ProductController::class)->middleware('permission:products.manage');
    Route::resource('categories', CategoryController::class)->middleware('permission:categories.manage');
    Route::resource('coupons', CouponController::class)->middleware('permission:coupons.manage');
    Route::resource('banners', BannerController::class)->middleware('permission:marketing.manage');

    // Inventory
    Route::controller(InventoryController::class)->prefix('inventory')->name('inventory.')
        ->middleware('permission:inventory.manage')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::patch('/{product}', 'update')->name('update');
        });

    // Customers
    Route::controller(CustomerController::class)->prefix('customers')->name('customers.')
        ->middleware('permission:customers.view')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{user}', 'show')->name('show');
            Route::patch('/{user}/toggle', 'toggle')->middleware('permission:customers.manage')->name('toggle');
        });

    // Custom crochet requests
    Route::controller(CustomRequestController::class)->prefix('custom-requests')->name('custom.')
        ->middleware('permission:custom.manage')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{customRequest:request_number}', 'show')->name('show');
            Route::patch('/{customRequest:request_number}/status', 'updateStatus')->name('status');
            Route::post('/{customRequest:request_number}/quote', 'quote')->name('quote');
            Route::post('/{customRequest:request_number}/convert', 'convert')->name('convert');
        });

    // Reports
    Route::controller(ReportController::class)->prefix('reports')->name('reports.')
        ->middleware('permission:reports.view')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/sales', 'sales')->name('sales');
            Route::get('/inventory', 'inventory')->name('inventory');
            Route::get('/customers', 'customers')->name('customers');
            Route::get('/export/{type}', 'export')->name('export');
        });

    // Marketing (newsletter, contact messages)
    Route::controller(MarketingController::class)->prefix('marketing')->name('marketing.')
        ->middleware('permission:marketing.manage')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/subscribers', 'subscribers')->name('subscribers');
            Route::get('/messages', 'messages')->name('messages');
            Route::patch('/messages/{message}/read', 'markRead')->name('messages.read');
        });

    // Settings
    Route::controller(SettingController::class)->prefix('settings')->name('settings.')
        ->middleware('permission:settings.manage')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/', 'update')->name('update');
        });

    // Roles & permissions
    Route::resource('roles', RoleController::class)->middleware('permission:roles.manage');
    Route::resource('staff', \App\Http\Controllers\Admin\StaffController::class)->middleware('permission:roles.manage');
});
