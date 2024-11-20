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
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\GraphicController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ReportsSellerCustomerController;
use App\Http\Controllers\ReportsCouponsController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
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


Route::match(['get', 'post'], 'winner-number', function (Request $request) {
    $number = $request->number; 
    $users = DB::table('assign_customer_coupons')
    ->select('assign_customer_coupons.customer_id', DB::raw("CONCAT(users.name, ' ', users.lname) AS customer_name"), 'users.city', 'users.phone_number')
            ->leftJoin('users', 'users.id', '=', 'assign_customer_coupons.customer_id')
            ->where('coupon_number', $number)->first();

    return view('winner-form', ['data' => $users]);
});

Route::group(['middleware' => 'auth'], function () {
    // Route::get('home', function () {
    //     return view('home');
    // });
    Route::get('home', [HomeController::class, 'index'])->name('home.index');
    Route::get('/zoom-meeting', [HomeController::class, 'zoomMeeting'])->name('home.zoomMeeting');
    Route::put('/home/update/{id}', [HomeController::class, 'update'])->name('home.update');

    // Route::match(['post', 'put'], '/home/update', [HomeController::class, 'update'])->name('home.update');



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
    Route::resource('gallery', GalleryController::class);
    Route::resource('asset', AssetController::class);
    Route::resource('broadcast', BroadcastController::class);
    Route::resource('graphic', GraphicController::class);
    Route::resource('reports', ReportsController::class);
    Route::resource('reportssellercustomer', ReportsSellerCustomerController::class);
    Route::resource('reportscoupons', ReportsCouponsController::class);
    Route::resource('testing', TestingController::class);
    Route::delete('reportssellercustomer/{param1}/{param2}', [ReportsSellerCustomerController::class, 'destroy']);

    Route::get('graphic', [GraphicController::class, 'index'])->name('graphic.index');
    Route::get('broadcast', [BroadcastController::class, 'index'])->name('broadcast.index');
    Route::get('asset', [AssetController::class, 'index'])->name('asset.index');
    Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
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
    Route::post('ajaxRequest', [OrderController::class, 'checkPrevCoupons'])->name('ajaxRequest.post');
    Route::get('/details/{id}', [SellerController::class, 'details']);

    Route::post('import-users', [SellerController::class, 'importUsers']);
    Route::post('import-coupons', [CustomercouponController::class, 'importCoupons']);
    Route::get('/slots/{id}', [OrderController::class, 'slots']);
    Route::put('/updateslot/{id}', [OrderController::class, 'updateslot']);

    Route::match(['get', 'post'], '/addBill', [BillController::class, 'addBill'])->name('addBill');
    Route::post('bill/insertData', [BillController::class, 'insertData']);
    
    Route::match(['get', 'post'], '/addAsset', [AssetController::class, 'addAsset'])->name('addAsset');
    Route::post('asset/insertData', [AssetController::class, 'insertData']);
    Route::get('/asset/points/{id}/{idd}', [AssetController::class, 'getPoints'])->name('asset.getPoints');

    // Route::get('bill/savekeywordDetails', [BillController::class, 'bill/savekeywordDetails']);
    // Route::post('reports/sellerCustomerCoupons', [ReportsController::class, 'sellerCustomerCoupons'])->name('reports.sellerCustomerCoupons');
    // Route::get('reports/sellerCustomerCouponsList', [ReportsController::class, 'sellerCustomerCouponsList'])->name('reports.sellerCustomerCouponsList');
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
