<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DBAController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProductLineController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ScheduleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| 這裡定義了應用程式的 API 路由
|
*/

// 取得已認證的使用者資料
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 測試 API
Route::get('/test', [TestController::class, 'test']);

// 從 ERP 系統同步資料
Route::get('/syncErpData', [DBAController::class, 'syncErpData']);

// 取得產線資料
Route::get('/getProductLines', [ProductLineController::class, 'get']);
// 取得機台資料
Route::get('/getMachines', [MachineController::class, 'get']);
// 取得工單資料
Route::get('/getOrders', [OrderController::class, 'get']);

// 建立排程
Route::post('/createSchedules', [ScheduleController::class, 'create']);
// 取得排程
Route::get('/getSchedules', [ScheduleController::class, 'get']);
// 更新排程
Route::put('/updateSchedules', [ScheduleController::class, 'update']);

// 取得機台工時
Route::get('/getMachineWorkHours', [MachineController::class, 'getMachineWorkHours']);

// 取得達成率
Route::get('/getAchievementRate', [ScheduleController::class, 'getAchievementRate']);
