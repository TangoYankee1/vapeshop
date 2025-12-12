<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// AUTH
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// ADMIN LOGIN
Route::post('/admin/login', [AdminController::class, 'login']);

// PUBLIC DATA
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

// PROTECTED ROUTES
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
});

// ADMIN ROUTES
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', [ReportController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('admin.reports.sales');
    Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('admin.reports.inventory');
    Route::get('reports/orders', [ReportController::class, 'orders'])->name('admin.reports.orders');
    Route::get('reports/staff', [ReportController::class, 'staff'])->name('admin.reports.staff');
    Route::apiResource('staff', StaffController::class)->except(['destroy']);
});

// STAFF ROUTES
Route::middleware(['auth:sanctum', 'role:staff'])->prefix('staff')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('promotions', PromoController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('banners', BannerController::class);
    Route::apiResource('users', UserController::class);
});
