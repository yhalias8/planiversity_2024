<?php

use App\Http\Controllers\ApiController\CategoryController;
use App\Http\Controllers\ApiController\InquiryController;
use App\Http\Controllers\ApiController\MessageController;
use App\Http\Controllers\ApiController\MigrationController;
use App\Http\Controllers\ApiController\OrderController;
use App\Http\Controllers\ApiController\PaypalPaymentController;
use App\Http\Controllers\ApiController\ServiceController;
use App\Http\Controllers\ApiController\StripePaymentController;
use App\Http\Controllers\ApiController\WishController;
use App\Http\Controllers\ApiController\BlogController;
use App\Http\Controllers\ApiController\TripInfoController;
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

Route::group(['prefix' => 'payment'], function () {
    Route::post('/stripe', [StripePaymentController::class, 'store']);
    Route::post('/paypal/instance-create', [PaypalPaymentController::class, 'instance_create']);
    Route::post('/paypal/instance-execute', [PaypalPaymentController::class, 'instance_execute']);
});

Route::group(['prefix' => 'order'], function () {
    Route::get('/{id}', [OrderController::class, 'single']);
});

Route::group(['prefix' => 'message'], function () {
    Route::get('/load', [MessageController::class, 'index']);
    Route::get('/head', [MessageController::class, 'head']);
    Route::get('/recipients', [MessageController::class, 'recipientList']);
    Route::post('/process', [MessageController::class, 'message_process']);
    Route::post('/start', [MessageController::class, 'message_start']);
    Route::post('/seen', [MessageController::class, 'message_seen']);
    Route::get('/notification', [MessageController::class, 'message_notification']);
    Route::post('/group-add-message', [MessageController::class, 'group_message']);
});

Route::group(['prefix' => 'migration'], function () {
    Route::post('/process', [MigrationController::class, 'index']);
    Route::post('/connect', [MigrationController::class, 'connect']);
    Route::post('/status', [MigrationController::class, 'status_action']);
});

Route::group(['prefix' => 'inquiry'], function () {
    Route::post('/seller', [InquiryController::class, 'index']);
    Route::post('/agent', [InquiryController::class, 'agent']);
});

Route::group(['prefix' => 'blog'], function () {
    Route::get('/posts', [BlogController::class, 'index']);
    Route::get('/categories', [BlogController::class, 'blogCategoryList']);
    Route::get('/single', [BlogController::class, 'single']);
});

Route::group(['prefix' => 'trip-info'], function () {
    Route::get('/attendees', [TripInfoController::class, 'index']);
    Route::get('/comments', [TripInfoController::class, 'comment']);
    Route::post('/comment', [TripInfoController::class, 'commentProcess']);
});

Route::get('/', function () {
    echo "Welcome";
});
