<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\BroadcastController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\OrderController;

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

// Route untuk mendapatkan data user yang terautentikasi
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// OTP Routes
Route::prefix('otp')->group(function () {
    Route::post('request', [OtpController::class, 'requestOtp']);
    Route::post('verify', [OtpController::class, 'verifyOtp']);
});

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// Routes untuk Member
Route::middleware(['auth:sanctum'])->group(function () {
    // Categories
    Route::get('categories', [CategoryController::class, 'index']);
    
    // Sliders
    Route::get('sliders', [SliderController::class, 'index']);
    
    // Products
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    
    // Orders
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders', [OrderController::class, 'index']);
});

// Routes untuk Admin
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Categories (CRUD)
    Route::resource('categories', CategoryController::class)->except(['index']);
    
    // Sliders (CRUD)
    Route::resource('sliders', SliderController::class)->except(['index']);
    
    // Products (CRUD)
    Route::resource('products', ProductController::class)->except(['index', 'show']);
    
    // Members
    Route::resource('members', MemberController::class);
    
    // Orders (kelola status)
    Route::get('orders', [OrderController::class, 'index']);
    Route::put('orders/{id}', [OrderController::class, 'update']);
    
    // Reports
    Route::get('reports', [ReportController::class, 'index']);
});

Route::post('send/all', [BroadcastController::class, 'sendBroadcast']);
Route::post('send/age', [BroadcastController::class, 'broadcastByAge']);
