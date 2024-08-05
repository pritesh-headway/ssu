<?php

use App\Http\Controllers\API\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\ProfileController;
use App\Http\Controllers\API\V1\BannerController;

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


Route::post('v1/sendOtp', [ApiController::class, 'sendOtp']);
Route::post('v1/login', [ApiController::class, 'logincheck']);
Route::post('v1/getBanners', [ApiController::class, 'getBanners']);
Route::post('v1/getEvents', [ApiController::class, 'getEvents']);
Route::post('v1/getSellerList', [ApiController::class, 'getSellerList']);
Route::post('v1/getCustomerList', [ApiController::class, 'getCustomerList']);
Route::post('v1/getDashboardData', [ApiController::class, 'getDashboardData']);
Route::post('v1/getCouponsOrderList', [ApiController::class, 'getCouponsOrderList']);
Route::post('v1/getEventDetail', [ApiController::class, 'getEventDetails']);
Route::post('v1/addSeller', [ApiController::class, 'addSeller']);
Route::post('v1/addCustomer', [ApiController::class, 'addCustomer']);
Route::post('v1/assignCoupon', [ApiController::class, 'assignCoupon']);
Route::post('v1/getAllCouponslist', [ApiController::class, 'getAllCouponslist']);
Route::post('v1/orderCoupon', [ApiController::class, 'orderCoupon']);
Route::post('v1/getEventVideoList', [ApiController::class, 'getEventVideoList']);
Route::post('v1/getEventImagesList', [ApiController::class, 'getEventImagesList']);
Route::post('v1/getAssignedCouponslist', [ApiController::class, 'getAssignedCouponslist']);
Route::post('v1/getCustomerCouponsList', [ApiController::class, 'getCustomerCouponsList']);

//profiles
Route::post('v1/profile/upload', [ProfileController::class, 'uploadProfileImage']);
Route::post('v1/profile/update', [ProfileController::class, 'updateProfile']);
Route::post('v1/profile/getProfile', [ProfileController::class, 'getProfileDetails']);


Route::post('/v1/logout', [ApiController::class, 'logout']);