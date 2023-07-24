<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChaletController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ImageController;
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

Route::middleware('auth:sanctum')->group(function () {

    //auth
    Route::get('/token/refresh', [AuthController::class, "refreshToken"]);
    Route::post('/register/subadmin', [AuthController::class, "registerSubAdmin"])->middleware('admin');

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
});

// next
// expire token, fill data, throttle, features(common and chalet), chalet images
// admin users list, update user information(phone, name, password, fcm, address, image, country and city)
// block and increase report count
// booking(new ,approve, cancel, reject, pay, completed), list(admin, user, chalet)
// payments
// review(new, delete, list)
// reports(new, list)
// notifications(list, read)
// send code to phone number 2nd factor authentication
// deploy

