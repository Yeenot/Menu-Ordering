<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->group(function () {
    Route::apiResource('categories', App\Http\Controllers\Api\CategoryController::class);
    Route::apiResource('items', App\Http\Controllers\Api\ItemController::class);
    Route::get('coupons/{code}', [App\Http\Controllers\Api\CouponController::class, 'show']);
    Route::get('orders/{code}', [App\Http\Controllers\Api\OrderController::class, 'show']);
    Route::post('checkout', [App\Http\Controllers\Api\OrderController::class, 'store']);
});

