<?php

use App\Http\Controllers\ApiController\CategoryController;
use App\Http\Controllers\ApiController\ServiceController;
use App\Http\Controllers\ApiController\WishController;
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

Route::group(['prefix' => 'category'], function () {
    Route::get('/', [CategoryController::class, 'index']);
    // Route::get('/{id}', [PostController::class, 'show']);
    // Route::post('/', [PostController::class, 'store']);
    // Route::put('/{id}', [PostController::class, 'update']);
    // Route::delete('/{id}', [PostController::class, 'destroy']);
});

Route::group(['prefix' => 'service'], function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/wishlist', [ServiceController::class, 'wishlist']);
    Route::get('/{id}', [ServiceController::class, 'single']);
    // Route::post('/', [PostController::class, 'store']);
    // Route::put('/{id}', [PostController::class, 'update']);
    // Route::delete('/{id}', [PostController::class, 'destroy']);
});

Route::group(['prefix' => 'wishlist'], function () {
    // Route::get('/', [WishController::class, 'index']);
    // Route::get('/{id}', [PostController::class, 'show']);
    Route::post('/', [WishController::class, 'store']);
    // Route::put('/{id}', [PostController::class, 'update']);
    // Route::delete('/{id}', [PostController::class, 'destroy']);
});

Route::get('/', function () {
    echo "Welcome";
});
