<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminRoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('books')->group(function(){
    Route::get('', [BookController::class,'index']);
    Route::get('{id}', [BookController::class,'show']);
    Route::get('search', [BookController::class,'search']);
    Route::post('store', [BookController::class,'store']);
    Route::post('update',[BookController::class,'update']);
    Route::delete('{book}',[BookController::class,'destroy']);
});

Route::prefix('users')->group(function(){
    Route::get('', [UserController::class,'index']);
    Route::get('{id}', [UserController::class,'show']);
    //Route::get('search', [UserController::class,'search']);
    Route::post('store', [UserController::class,'store']);
    Route::post('update',[UserController::class,'update']);
    Route::delete('{user}',[UserController::class,'destroy']);
});

Route::prefix('fields')->group(function(){
    Route::get('', [FieldController::class,'index']);
    Route::get('{id}', [FieldController::class,'show']);
    //Route::get('search', [FieldController::class,'search']);
    Route::post('store', [FieldController::class,'store']);
    Route::post('update',[FieldController::class,'update']);
    Route::delete('{field}',[FieldController::class,'destroy']);
});

Route::prefix('reservations')->group(function(){
    Route::get('', [ReservationController::class,'index']);
    Route::put('{reservation}',[ReservationController::class,'update']);
    Route::delete('{reservation}', [ReservationController::class,'destroy']);
});

Route::prefix('notifications')->group(function(){
    Route::get('', [NotificationsController::class,'index']);
    Route::get('{id}', [NotificationsController::class,'show']);
    Route::post('store', [NotificationsController::class,'store']);
    Route::post('update',[NotificationsController::class,'update']);
    Route::delete('{user}',[NotificationsController::class,'destroy']);
});

Route::prefix('search')->group(function(){
    Route::get('/book', [SearchController::class,'book']);
    Route::get('/user', [SearchController::class,'user']);
    Route::get('/queue',[SearchController::class,'queue']);
    Route::get('/reservation', [SearchController::class,'reservation']);
    Route::get('/log', [SearchController::class,'log']);
    Route::get('/field', [SearchController::class,'field']);
});