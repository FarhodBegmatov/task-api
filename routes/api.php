<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function (){
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::group(['middleware' => 'jwt.auth'], function (){
        Route::group(['prefix' => 'auth'], function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::post('me', [AuthController::class, 'me']);
        });

        Route::group(['prefix' => 'users'], function (){
            Route::get('', [UserController::class, 'index']);
            Route::post('', [UserController::class, 'store']);
            Route::get('/{user}', [UserController::class, 'show']);
            Route::put('/{user}', [UserController::class, 'update']);
            Route::delete('/{user}',[UserController::class, 'destroy']);
        });

        Route::group(['prefix' => 'brands'], function (){
            Route::get('', [BrandController::class, 'index']);
            Route::post('', [BrandController::class, 'store']);
            Route::get('/{brand}', [BrandController::class, 'show']);
            Route::post('/{brand}', [BrandController::class, 'update']);
            Route::delete('/{brand}',[BrandController::class, 'destroy']);
        });


        Route::group(['prefix' => 'branches'], function (){
            Route::get('', [BranchController::class, 'index']);
            Route::post('', [BranchController::class, 'store']);
            Route::get('/{branch}', [BranchController::class, 'show']);
            Route::post('/{branch}', [BranchController::class, 'update']);
            Route::delete('/{branch}',[BranchController::class, 'destroy']);
        });

        Route::get('branch-count-by-region', [BranchController::class, 'branchCountByRegion']);
        Route::get('branch-count-by-district', [BranchController::class, 'branchCountByDistrict']);


    });
});
