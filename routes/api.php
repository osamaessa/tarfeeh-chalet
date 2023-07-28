<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChaletController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;


//country
Route::get('/country/list', [CountryController::class, "list"]);

//auth
Route::post('/admin/register', [AuthController::class, "registerAdmin"]);
Route::post('/register', [AuthController::class, "register"]);
Route::post('/register/chalet', [AuthController::class, "registerChaletUser"]);
Route::post('/login', [AuthController::class, "login"]);
Route::post('/verify', [AuthController::class, "verify"]);
Route::post('/password/forget', [AuthController::class, "forgetPassword"]);
Route::post('/password/forget/verify', [AuthController::class, "forgetPasswordVerify"]);
Route::post('/password/reset', [AuthController::class, "resetPassword"]);

//review
Route::get('/review/list', [ReviewController::class, "list"]);

//report
Route::post('/report/add', [ReportController::class, "add"]);

Route::middleware('auth:sanctum')->group(function () {


    //auth
    Route::get('/token/refresh', [AuthController::class, "refreshToken"]);
    Route::post('/register/subadmin', [AuthController::class, "registerSubAdmin"])->middleware('admin');

    //user
    Route::get('/user/list', [UserProfileController::class, "users"])->middleware('subadmin');
    Route::get('/user/details', [UserProfileController::class, "userDetails"])->middleware('subadmin');
    Route::get('/profile', [UserProfileController::class, "profile"]);
    Route::put('/profile/name', [UserProfileController::class, "updateName"]);
    Route::put('/profile/fcm', [UserProfileController::class, "updateFcm"]);
    Route::put('/profile/password', [UserProfileController::class, "updatePassword"]);
    Route::put('/profile/address', [UserProfileController::class, "updateAddress"]);
    Route::put('/profile/image', [UserProfileController::class, "updateImage"]);
    Route::put('/profile/block', [UserProfileController::class, "block"])->middleware('subadmin');
    Route::put('/profile/unblock', [UserProfileController::class, "unblock"])->middleware('subadmin');
    Route::put('/profile/reports/increase', [UserProfileController::class, "increaseReportCount"])->middleware('subadmin');

    //booking
    Route::post('/booking/add', [BookingController::class, "add"]);
    Route::put('/booking/approve', [BookingController::class, "approve"])->middleware('chalet');
    Route::put('/booking/cancel', [BookingController::class, "cancel"]);
    Route::put('/booking/reject', [BookingController::class, "reject"])->middleware('chalet');
    Route::put('/booking/complete', [BookingController::class, "complete"]);
    Route::get('/booking/admin/list', [BookingController::class, "adminList"])->middleware('subadmin');
    Route::get('/booking/chalet/list', [BookingController::class, "chaletList"])->middleware('chalet');
    Route::get('/booking/user/list', [BookingController::class, "userList"]);
    Route::put('/booking/review/seen', [BookingController::class, "reviewSeen"]);

    //review
    Route::post('/review/add', [ReviewController::class, "add"]);
    Route::post('/review/delete', [ReviewController::class, "delete"]);

    //image
    Route::post('/image/upload', [ImageController::class, "upload"]);

    //country
    Route::post('/country/add', [CountryController::class, "addCountry"])->middleware('admin');
    Route::post('/city/add', [CountryController::class, "addCity"])->middleware('admin');

    //chalet
    Route::get('/chalet/profile', [ChaletController::class, "profile"])->middleware('chalet');
    Route::post('/chalet/setup', [ChaletController::class, "setup"])->middleware('chalet');
    Route::put('/chalet/about', [ChaletController::class, "about"])->middleware('chalet');
    Route::put('/chalet/image', [ChaletController::class, "image"])->middleware('chalet');
    Route::put('/chalet/max', [ChaletController::class, "maxVisitorsNumber"])->middleware('chalet');
    Route::put('/chalet/times', [ChaletController::class, "times"])->middleware('chalet');
    Route::put('/chalet/facebook', [ChaletController::class, "facebook"])->middleware('chalet');
    Route::put('/chalet/instagram', [ChaletController::class, "instagram"])->middleware('chalet');
    Route::put('/chalet/video', [ChaletController::class, "video"])->middleware('chalet');
    Route::put('/chalet/address', [ChaletController::class, "address"])->middleware('chalet');
    Route::put('/chalet/downpayment', [ChaletController::class, "downPayment"])->middleware('chalet');
    Route::put('/chalet/image/id', [ChaletController::class, "imageId"])->middleware('chalet');
    Route::put('/chalet/image/license', [ChaletController::class, "license"])->middleware('chalet');
    Route::put('/chalet/pricing', [ChaletController::class, "pricing"])->middleware('chalet');
    Route::put('/chalet/block', [ChaletController::class, "block"])->middleware('admin');
    Route::put('/chalet/unblock', [ChaletController::class, "unblock"])->middleware('admin');
    Route::put('/chalet/approve', [ChaletController::class, "approve"])->middleware('admin');

    Route::get('/chalet/list', [ChaletController::class, "list"])->middleware('admin');
    Route::get('/chalet/list/ready', [ChaletController::class, "readyList"])->middleware('admin');

    //feature
    Route::post('/feature/add', [FeatureController::class, "add"])->middleware('subadmin');
    Route::put('/feature/update', [FeatureController::class, "update"])->middleware('subadmin');
    Route::post('/feature/delete', [FeatureController::class, "delete"])->middleware('subadmin');
    Route::get('/feature/list', [FeatureController::class, "list"])->middleware('chalet');

    Route::post('/chalet/feature/add', [ChaletController::class, "addFeature"])->middleware('chalet');
    Route::post('/chalet/feature/delete', [ChaletController::class, "deleteFeature"])->middleware('chalet');

    //chalet images
    Route::post('/chalet/image/add', [ChaletController::class, "addImage"])->middleware('chalet');
    Route::post('/chalet/image/delete', [ChaletController::class, "deleteImage"])->middleware('chalet');

    //report
    Route::put('/report/solve', [ReportController::class, "solve"])->middleware('subadmin');
    Route::get('/report/list', [ReportController::class, "list"])->middleware('subadmin');
});

// next
// fill data, features(update fillable, common and chalet), chalet images ----------------- done
// admin users list, update user information(name, password, fcm, address, image, country and city) ----------------- done
// block and increase report count ----------------- done
// booking(new ,approve, cancel, reject, pay, completed), list(admin, user, chalet) ----------------- done
// payments
// review(new, delete, list) ----------------- done
// reports(new, list)
// notifications(list, read)
// send code to phone number 2nd factor authentication
// remove unused images
// deploy
