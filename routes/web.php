<?php

// use App\Http\Controllers\API\BannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomercouponController;
use App\Http\Controllers\SlabController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ContactusController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\SocialController;
use Symfony\Component\HttpFoundation\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/** for side bar menu active */
// function set_active($route)
// {
//     if (is_array($route)) {
//         return in_array(Request::path(), $route) ? 'active' : '';
//     }
//     return Request::path() == $route ? 'active' : '';
// }

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('home', function () {
        return view('home');
    });
    Route::get('home', function () {
        return view('home');
    });

    Route::resource('banner', BannerController::class);
    Route::resource('event', EventController::class);
    Route::resource('coupon', CouponController::class);
    Route::resource('seller', SellerController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('order', OrderController::class);
    Route::resource('customercoupon', CustomercouponController::class);
    Route::resource('slab', SlabController::class);
    Route::resource('bill', BillController::class);
    Route::resource('document', DocumentController::class);
    Route::resource('contactus', ContactusController::class);
    Route::resource('prize', PrizeController::class);
    Route::resource('cms', CmsController::class);
    Route::resource('notice', NoticeController::class);
    Route::resource('social', SocialController::class);

    Route::get('social', [SocialController::class, 'index'])->name('social.index');
    Route::get('notice', [NoticeController::class, 'index'])->name('notice.index');
    Route::get('cms', [CmsController::class, 'index'])->name('cms.index');
    Route::get('prize', [PrizeController::class, 'index'])->name('prize.index');
    Route::get('contactus', [ContactusController::class, 'index'])->name('contactus.index');
    Route::get('document', [DocumentController::class, 'index'])->name('document.index');
    Route::get('bill', [BillController::class, 'index'])->name('bill.index');
    Route::get('slab', [SlabController::class, 'index'])->name('slab.index');
    Route::get('customercoupon', [CustomercouponController::class, 'index'])->name('customercoupon.index');
    Route::get('order', [OrderController::class, 'index'])->name('order.index');
    Route::get('customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('seller', [SellerController::class, 'index'])->name('seller.index');
    Route::get('coupon', [CouponController::class, 'index'])->name('coupon.index');
    Route::get('event', [EventController::class, 'index'])->name('event.index');
    Route::get('banner', [BannerController::class, 'index'])->name('banner.index');

    // Route::post('checkPrevCoupons', [OrderController::class, 'checkPrevCoupons'])->name('order.checkPrevCoupons');
    // Route::post('submit-post', [OrderController::class, 'checkPrevCoupons'])->name('checkPrevCoupons');
    Route::post('ajaxRequest', [OrderController::class, 'checkPrevCoupons'])->name('ajaxRequest.post');



});

Auth::routes();
Route::group(['namespace' => 'App\Http\Controllers\Auth'], function () {
    // ----------------------------login -----------------------------//
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'login')->name('login');
        Route::post('login', 'authenticate');
        Route::get('logout', 'logout')->name('logout');
    });

    // ----------------------------- register -------------------------//
    Route::controller(RegisterController::class)->group(function () {
        Route::get('register', 'register')->name('register');
        Route::post('register', 'storeUser')->name('register');
    });
});

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    // ---------------------- main dashboard ---------------------//
    Route::controller(HomeController::class)->group(function () {
        Route::get('\home', 'index')->middleware('auth')->name('home');
    });
    // ---------------------- user controll ---------------------//
    Route::controller(UserManagementController::class)->group(function () {
        Route::get('users-profile', 'userProfilePage')->middleware('auth')->name('users-profile');
    });
});
