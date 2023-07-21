<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//image
Route::post('/image/upload', [ImageController::class, "upload"]);

//country
Route::post('/country/add', [CountryController::class, "addCountry"]);
Route::post('/city/add', [CountryController::class, "addCity"]);
Route::get('/country/list', [CountryController::class, "list"]);
