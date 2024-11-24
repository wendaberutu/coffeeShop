<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BroadcastController;
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
| Routes for API endpoints.
| All routes are assigned the "api" middleware group.
*/

/*
|--------------------------------------------------------------------------
| Authenticated User Route
|--------------------------------------------------------------------------
| Get the authenticated user's information.
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| OTP Routes
|--------------------------------------------------------------------------
| Routes for OTP requests and verification.
*/
Route::prefix('otp')->group(function () {
    Route::post('request', [OtpController::class, 'requestOtp']);
    Route::post('verify', [OtpController::class, 'verifyOtp']);
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
| Routes for authentication actions (login, register, logout, etc.).
*/
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
| Routes restricted to admin users.
*/
Route::prefix('admin')->middleware(['auth:api', 'admin'])->group(function () {
    Route::resource('members', MemberController::class)->except(['create', 'edit']);
    Route::resource('reports', ReportController::class)->except(['create', 'edit']);
    Route::post('send/all', [BroadcastController::class, 'sendBroadcast']);
    Route::post('send/age', [BroadcastController::class, 'broadcastByAge']);
});

/*
|--------------------------------------------------------------------------
| Member Routes (Protected)
|--------------------------------------------------------------------------
| Routes restricted to member users.
*/
Route::prefix('member')->middleware(['auth:api', 'member'])->group(function () {
    Route::resource('orders', OrderController::class)->except(['create', 'edit']);
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Routes accessible without authentication.
*/
Route::prefix('public')->group(function () {
    Route::get('products', [ProductController::class, 'index']); // List all products
    Route::get('products/{id}', [ProductController::class, 'show']); // Get product details by ID
});

/*
|--------------------------------------------------------------------------
| Product Management Routes (Admin Only)
|--------------------------------------------------------------------------
| Routes for managing products, restricted to admin users.
*/
Route::prefix('products')->middleware(['auth:api', 'admin'])->group(function () {
    Route::post('/', [ProductController::class, 'store']); // Add new product
    Route::put('/{id}', [ProductController::class, 'update']); // Update product by ID
    Route::delete('/{id}', [ProductController::class, 'destroy']); // Delete product by ID
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
| Return a JSON response if a route is not found.
*/
Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found. Please check the URL and method.',
    ], 404);
});
