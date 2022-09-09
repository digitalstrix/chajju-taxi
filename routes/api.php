<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controller\UserController;

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

Route::group(['middleware' => 'auth:sanctum'], function(){
    //All secure URL's
    Route::post("/updateUser",'App\Http\Controllers\UserController@updateUser');
    Route::post("/deleteUser",'App\Http\Controllers\UserController@deleteUser');
    Route::post("/getRiders",'App\Http\Controllers\RiderController@getRiders');
    Route::post("/getRider",'App\Http\Controllers\RiderController@getRider');
    Route::post("/ridersRides",'App\Http\Controllers\RiderController@ridersRides');
    Route::post("/getDrivers",'App\Http\Controllers\DriverController@getDrivers');
    Route::post("/getDriver",'App\Http\Controllers\DriverController@getDriver');
    Route::post("/driversRides",'App\Http\Controllers\DriverController@driversRides');
    Route::post("/uploadDoc",'App\Http\Controllers\DriverController@uploadDoc');
    Route::post("/getDoc",'App\Http\Controllers\DriverController@getDoc');
    Route::post("/getRideDetail",'App\Http\Controllers\GeneralController@getRideDetail');
    Route::post("/allRides",'App\Http\Controllers\GeneralController@allRides');
    Route::post("/createRide",'App\Http\Controllers\GeneralController@createRide');
    Route::post("/updateRide",'App\Http\Controllers\GeneralController@updateRide');
    Route::post("/cancelledRides",'App\Http\Controllers\GeneralController@cancelledRides');
    Route::post("/pendingRide",'App\Http\Controllers\GeneralController@pendingRide');
    Route::post("/scheduledRide",'App\Http\Controllers\GeneralController@scheduledRide');
    Route::post("/completedRide",'App\Http\Controllers\GeneralController@completedRide');
    Route::post("/addVehicle",'App\Http\Controllers\GeneralController@addVehicle');
    Route::post("/updateVehicle",'App\Http\Controllers\GeneralController@updateVehicle');
    Route::post("/deleteVehicle",'App\Http\Controllers\GeneralController@deleteVehicle');
    Route::post("/addDriverPayment",'App\Http\Controllers\DriverController@addDriverPayment');
    Route::post("/updateDriverPayment",'App\Http\Controllers\DriverController@updateDriverPayment');
    Route::post("/getDriverAccount",'App\Http\Controllers\DriverController@getDriverAccount');
    Route::post("/addDriverRating",'App\Http\Controllers\RatingController@addDriverRating');
    Route::post("/deleteDriverRating",'App\Http\Controllers\RatingController@deleteDriverRating');
    Route::post("/getReviewByDriverId",'App\Http\Controllers\RatingController@getReviewByDriverId');
    Route::post("/getReviewByRiderId",'App\Http\Controllers\RatingController@getReviewByRiderId');
    Route::post("/addRiderRating",'App\Http\Controllers\RatingController@addRiderRating');
    Route::post("/deleteRiderRating",'App\Http\Controllers\RatingController@deleteRiderRating');
    Route::post("/riderReviewByDriverId",'App\Http\Controllers\RatingController@riderReviewByDriverId');
    Route::post("/riderReviewByRiderId",'App\Http\Controllers\RatingController@riderReviewByRiderId');
    Route::post("/addCoupon",'App\Http\Controllers\CouponController@addCoupon');
    Route::post("/updateCoupon",'App\Http\Controllers\CouponController@updateCoupon');
    Route::get("/getCoupons",'App\Http\Controllers\CouponController@getCoupons');
    Route::post("/availCoupon",'App\Http\Controllers\CouponController@availCoupon');
    Route::post("/addPushNotification",'App\Http\Controllers\SiteController@addPushNotification');
    Route::post("/getPushNotification",'App\Http\Controllers\SiteController@getPushNotification');
    Route::post("/upadateSite",'App\Http\Controllers\SiteController@upadateSite');
   
    });


Route::post("/login",'App\Http\Controllers\UserController@index');
Route::post("/register",'App\Http\Controllers\UserController@register');
Route::get("/getSite",'App\Http\Controllers\SiteController@getSite');
Route::post("/otplogin",'App\Http\Controllers\UserController@otplogin');
Route::post("/otpValidate",'App\Http\Controllers\UserController@otpValidate');