<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CartItemController;

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

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [LoginController::class, 'register']);
    // Route::post('products', [ProductApiController::class, 'productlist']);
    // Route::post('create', [ProductApiController::class, 'create']);
    Route::post('updatebyid/{id}', [ProductApiController::class, 'updatebyid']);
    Route::post('destroybyid/{id}', [ProductApiController::class, 'destroybyid']);

    Route::resource('products', ProductApiController::class);

    Route::resource('cartitems', CartItemController::class);

    Route::post('updatecartbyid/{id}', [CartItemController::class, 'updatebyid']);
    Route::post('destroycartbyid/{id}', [CartItemController::class, 'destroybyid']);
});
