<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterApiController;
use App\Http\Controllers\Auth\LoginApiController;
use App\Http\Controllers\Spot\SpotController;
use App\Http\Controllers\Camera\CameraController;
use App\Http\Controllers\Home\HomeController;

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

// 認証
Route::post('/register', [RegisterApiController::class, 'register']);
Route::post('/login', [LoginApiController::class, 'login']);
Route::post('/logout', [LoginApiController::class, 'logout']);
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/spots_register/{usersId}', [SpotController::class, 'spotsRegister']);
    Route::post('/spots_update/{spotsId}', [SpotController::class, 'spotsUpdate']);
    Route::get('/spots_delete/{spotsId}', [SpotController::class, 'spotsDelete']);
    Route::post('/cameras_register/{spotsId}', [CameraController::class, 'cameraRegister']);
    Route::get('/home_data/{usersId}', [HomeController::class, 'homeData']);
    Route::get('/spots_data/{spotsId}', [SpotController::class, 'spotsData']);
});
