<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;


//country
Route::get('/country/list', [CountryController::class, "list"]);

Route::post('/admin/register', [AuthController::class, "registerAdmin"]);
Route::post('/register', [AuthController::class, "register"]);
Route::post('/login', [AuthController::class, "login"]);
Route::post('/verify', [AuthController::class, "verify"]);

Route::middleware('auth:sanctum')->group(function () {

    //image
    Route::post('/image/upload', [ImageController::class, "upload"]);

    //country
    Route::post('/country/add', [CountryController::class, "addCountry"])->middleware('admin');
    Route::post('/city/add', [CountryController::class, "addCity"])->middleware('admin');
});
