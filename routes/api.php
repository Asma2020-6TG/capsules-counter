<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthUsersController;
use App\Http\Controllers\CapsulesController;

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

    Route::middleware('guest:api')->group(function (){
        Route::post('register',[AuthUsersController::class, 'register']);
        Route::post('login',[AuthUsersController::class, 'login']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::post('logout',[AuthUsersController::class, 'logout']);
        Route::post('changepassword',[AuthUsersController::class, 'changePassword']);
        Route::post('resetpassword',[AuthUsersController::class, 'resetPassword']);
        Route::get('capsulesdailyreport',[CapsulesController::class,'dailyReport']);
        Route::get('capsulesweeklyreport',[CapsulesController::class,'weeklyReport']);
        Route::get('capsulesmonthlyreport',[CapsulesController::class,'monthlyReport']);
});
