<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/getScheduleFakeData', [TestController::class, 'getScheduleFakeData']);
Route::get('/getProductLineFakeData', [TestController::class, 'getProductLineFakeData']);
