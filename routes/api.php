<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ActivitiesController;
use App\Http\Controllers\Api\SubscriptionsController;
use App\Http\Controllers\Api\UserController;

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

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function(){


    Route::get('/contents', [ContentController::class, 'index']);
    Route::get('/contents/{id}', [ContentController::class, 'show']);
    Route::post('/contents', [ContentController::class, 'store']);
    Route::put('/contents/{id}', [ContentController::class, 'update']);
    Route::delete('/contents/{id}', [ContentController::class, 'destroy']);

    Route::get('/activities', [ActivitiesController::class, 'index']);
    Route::get('/activities/{id}', [ActivitiesController::class, 'show']);
    Route::post('/activities', [ActivitiesController::class, 'store']);
    Route::put('/activities/{id}', [ActivitiesController::class, 'update']);
    Route::delete('/activities/{id}', [ActivitiesController::class, 'destroy']);

    Route::post('/subscriptions', [SubscriptionsController::class,'store']);
    Route::get('/subscriptions', [SubscriptionsController::class,'index']);
    Route::put('/subscriptions/{id}', [SubscriptionsController::class,'update']);
    Route::delete('/subscriptions/{id}', [SubscriptionsController::class,'destroy']);
    Route::get('/subscriptions/{id}', [SubscriptionsController::class,'getByID']);


    Route::get('/user', [UserController::class, 'read']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'delete']);
    Route::get('/user/{id}', [UserController::class, 'search']);

});
