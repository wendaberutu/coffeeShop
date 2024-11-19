<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\API\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/request-otp', [OtpController::class, 'requestOtp']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);

Route::group([
    'middleware'=> 'api',
    'prefix' => 'auth'
], function(){
    Route::post('admin', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login_member']);
});

// Menggunakan Route::resource untuk kategori
Route::group([
    'middleware' => 'api',
], function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('sliders', SliderController::class);
    Route::resource('products', ProductController::class);
    Route::resource('members', MemberController::class);
    Route::resource('orders', OrderController::class);

});
Route::get('reports', [ReportController::class, 'index']);