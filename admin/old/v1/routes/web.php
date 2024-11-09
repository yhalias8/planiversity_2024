<?php

use App\Http\Controllers\AdminBackend\ApplicationSettingsController;
use App\Http\Controllers\AdminBackend\Auth\LoginController;
use App\Http\Controllers\AdminBackend\HomeController as AdminHomeController;
use App\Http\Controllers\AdminBackend\UserListController;
use App\Http\Controllers\AdminBackend\CouponController;
use App\Http\Controllers\AdminBackend\MarketplaceCategoryController;
use App\Http\Controllers\AdminBackend\MarketplaceServiceController;
use App\Http\Controllers\AdminBackend\ProfileController;
use App\Http\Controllers\AdminBackend\TransactionsController;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\AdminBackend\Auth\LoginController as AdminLoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('clear', function () {
    \Artisan::call('view:clear');
    \Artisan::call('config:clear');
    \Artisan::call('route:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('optimize:clear');
    return 'Done';
});

Route::middleware(['guest:user', 'PreventBackHistory'])->group(function () {
    Route::get('/', [LoginController::class, 'loginForm'])->name('user.login');
    Route::post('/check', [LoginController::class, 'loginProcess'])->name('user.login.check');
});


Route::name('user.')->group(function () {

    Route::middleware(['auth:user', 'PreventBackHistory'])->group(function () {
        Route::get('/home', [AdminHomeController::class, 'index'])->name('home');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout.submit');

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserListController::class, 'index'])->name('list');
            Route::get('/{id}', [UserListController::class, 'single_user_view'])->name('single.user');
            Route::get('/list/user_payment', [UserListController::class, 'user_payment_list'])->name('payment.list');
            Route::post('/status/update', [UserListController::class, 'statusUpdate'])->name('status.update');
            Route::delete('/user/destroy', [UserListController::class, 'destroy'])->name('destroy.userList');
            //Route::put('/update', [AdminHomeController::class, 'update'])->name('update.home');
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', [ApplicationSettingsController::class, 'index'])->name('view');
        });

        Route::group(['prefix' => 'transactions'], function () {
            Route::get('/', [TransactionsController::class, 'index'])->name('view');
        });

        Route::group(['prefix' => 'coupon'], function () {
            Route::get('/', [CouponController::class, 'index'])->name('coupon.list');
            Route::post('/store', [CouponController::class, 'store'])->name('store.coupon');
            Route::put('/update', [CouponController::class, 'update'])->name('update.coupon');
            Route::delete('/destroy', [CouponController::class, 'destroy'])->name('destroy.coupon');
        });

        Route::group(['prefix' => 'marketplace-category'], function () {
            Route::get('/', [MarketplaceCategoryController::class, 'index'])->name('categeory.list');
            Route::post('/store', [MarketplaceCategoryController::class, 'store'])->name('store.category');
            Route::delete('/destroy', [MarketplaceCategoryController::class, 'destroy'])->name('destroy.category');
        });

        Route::group(['prefix' => 'marketplace-service'], function () {
            Route::get('/', [MarketplaceServiceController::class, 'index'])->name('service.list');
        });

        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', [ProfileController::class, 'index'])->name('view');
        });
    });
});



// Route::get('/', function () {
//     return view('welcome');
// });

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
