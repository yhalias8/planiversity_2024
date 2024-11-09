<?php

use App\Http\Controllers\AdminBackend\ApplicationSettingsController;
use App\Http\Controllers\AdminBackend\Auth\LoginController;
use App\Http\Controllers\AdminBackend\HomeController as AdminHomeController;
use App\Http\Controllers\AdminBackend\UserListController;
use App\Http\Controllers\AdminBackend\CouponController;
use App\Http\Controllers\AdminBackend\MarketplaceCategoryController;
use App\Http\Controllers\AdminBackend\MarketplaceOrderController;
use App\Http\Controllers\AdminBackend\MarketplaceReviewsController;
use App\Http\Controllers\AdminBackend\MarketplaceServiceController;
use App\Http\Controllers\AdminBackend\PaypalController;
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


Route::get('/lnk', function () {
    Artisan::call('storage:link');
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
            Route::get('/{id}', [UserListController::class, 'view'])->name('single.user');
            Route::post('/store', [UserListController::class, 'store'])->name('store.user');
            Route::get('/list/user_payment', [UserListController::class, 'user_payment_list'])->name('payment.list');
            Route::post('/status/update', [UserListController::class, 'statusUpdate'])->name('status.update');
            Route::delete('/user/destroy', [UserListController::class, 'destroy'])->name('destroy.userList');
            //Route::put('/update', [AdminHomeController::class, 'update'])->name('update.home');
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', [ApplicationSettingsController::class, 'index'])->name('view');
        });

        Route::group(['prefix' => 'transactions'], function () {
            Route::get('/billing', [TransactionsController::class, 'index'])->name('billing.transactions');
            Route::get('/service', [TransactionsController::class, 'service'])->name('service.transactions');
            Route::get('/order-payment', [TransactionsController::class, 'order_payment_list'])->name('order.payment');
        });

        Route::group(['prefix' => 'coupon'], function () {
            Route::get('/', [CouponController::class, 'index'])->name('coupon.list');
            Route::post('/store', [CouponController::class, 'store'])->name('store.coupon');
            Route::put('/update', [CouponController::class, 'update'])->name('update.coupon');
            Route::delete('/destroy', [CouponController::class, 'destroy'])->name('destroy.coupon');
        });

        Route::group(['prefix' => 'marketplace-category'], function () {
            Route::get('/', [MarketplaceCategoryController::class, 'index'])->name('categeory.list');
            Route::get('/cateoryList', [MarketplaceCategoryController::class, 'getCategoryData'])->name('categorylist.service');
            Route::post('/store', [MarketplaceCategoryController::class, 'store'])->name('store.category');
            Route::put('/update', [MarketplaceCategoryController::class, 'update'])->name('update.category');
            Route::delete('/destroy', [MarketplaceCategoryController::class, 'destroy'])->name('destroy.category');
        });

        Route::group(['prefix' => 'marketplace-service'], function () {
            Route::get('/', [MarketplaceServiceController::class, 'index'])->name('service.list');
            Route::get('/create', [MarketplaceServiceController::class, 'create'])->name('create.service');
            Route::get('/edit/{id}', [MarketplaceServiceController::class, 'edit'])->name('edit.service');
            Route::get('/edit/{id}/orders', [MarketplaceServiceController::class, 'orders'])->name('order.service');
            Route::get('/edit/{id}/reviews', [MarketplaceServiceController::class, 'reviews'])->name('review.service');
            Route::post('/store', [MarketplaceServiceController::class, 'store'])->name('store.service');
            Route::put('/update/{id}', [MarketplaceServiceController::class, 'update'])->name('update.service');
            Route::delete('/destroy', [MarketplaceServiceController::class, 'destroy'])->name('destroy.service');
        });

        Route::group(['prefix' => 'marketplace-order'], function () {
            Route::get('/', [MarketplaceOrderController::class, 'index'])->name('order.list');
            Route::get('/edit/{id}', [MarketplaceOrderController::class, 'edit'])->name('edit.order');
            Route::delete('/destroy', [MarketplaceOrderController::class, 'destroy'])->name('destroy.order');
        });

        Route::group(['prefix' => 'marketplace-review'], function () {
            Route::get('/', [MarketplaceReviewsController::class, 'index'])->name('review.list');
            Route::post('/store', [MarketplaceReviewsController::class, 'store'])->name('store.review');
            Route::put('/update', [MarketplaceReviewsController::class, 'update'])->name('update.review');
            Route::delete('/destroy', [MarketplaceReviewsController::class, 'destroy'])->name('destroy.review');
        });

        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', [ProfileController::class, 'index'])->name('view');
            Route::put('/profile-update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
            Route::put('/password-update', [ProfileController::class, 'passwordUpdate'])->name('password.update');
            Route::put('/profile-image-update', [ProfileController::class, 'profileImageUpdate'])->name('profile.image.update');
        });

        Route::group(['prefix' => 'paypal'], function () {
            Route::get('/', [PaypalController::class, 'index'])->name('create.paypal');
            Route::get('/process', [PaypalController::class, 'index'])->name('process.paypal');
        });
    });
});



// Route::get('/', function () {
//     return view('welcome');
// });

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
