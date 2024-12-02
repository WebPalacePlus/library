<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\QueuesController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminRoleMiddleware;

use App\Http\Middleware\StudentRoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::get('/login', function () {
    return view("login");
});
Route::get('/library', function () {
    return view("library");
})->middleware(StudentRoleMiddleware::class);
Route::post('/auth', [AuthController::class, 'login']);

Route::prefix('/admin')->middleware(AdminRoleMiddleware::class)->group(function () {
    Route::get('/dashboard', function () {
        return view("admin.home");
    });

    Route::prefix('/book')->group(function () {
        Route::get('/list', function () {
            return view('admin.book.list');
        });
        Route::get('/edit/{book}', [BookController::class, 'edit']);
        Route::get('/add', [BookController::class, 'create']);
    });

    Route::prefix('/user')->group(function () {
        Route::get('/list', function () {
            return view("admin.user.list");
        });
        Route::get('/edit/{user}', [UserController::class, 'edit']);
        Route::get('/add', [UserController::class, 'create']);
    });

    Route::prefix('/field')->group(function () {
        Route::get('/list', function () {
            return view("admin.field.list");
        });
        Route::get('/edit/{field}', [FieldController::class, 'edit']);
        Route::get('/add', [FieldController::class, 'create']);
    });

    Route::prefix('/reservation')->group(function () {
        Route::get('/list', function () {
            return view("admin.reservation.list");
        });
        Route::get('/', [ReservationController::class,'index']);
        Route::get('/return', [ReservationController::class, 'return']);
    });

    Route::prefix('/queue')->group(function(){
        Route::get('/list', function(){return view("admin.queues.list");});
        Route::get('/', [QueuesController::class,'index']);
        Route::get('/accept', [QueuesController::class,'accept']);
        Route::get('/reject', [QueuesController::class,'reject']);
    });

    Route::prefix('/notification')->group(function(){
        Route::get('/list', function(){return view("admin.notification.list");});
        Route::get('/', [NotificationsController::class,'index']);
        Route::get('/edit/{notification}', [NotificationsController::class, 'edit']);
        Route::get('/add', [NotificationsController::class, 'create']);
    });

    Route::prefix('/log')->group(function(){
        Route::get('/list', function(){return view("admin.log.list");});
        Route::get('/', [LogsController::class,'index']);
    });
});

Route::prefix('/user')->middleware(StudentRoleMiddleware::class)->group(function () {
    Route::get('/dashboard', function () {
        return view('user.home');
    });
    Route::get('/reserve/status/{book}', [ReservationController::class, 'show']);
    Route::get('/reserve/status', function () {
        return view("user.reserve");
    });
    Route::prefix('reservation')->group(function(){
        Route::get('/list', function() {return view("user.reserve");});
        Route::get('',[ReservationController::class,'usershow']);
    });
    Route::prefix('/notification')->group(function(){
        Route::get('/list',function(){return view("user.notifications");});
        Route::get('',[UserController::class,'notifications']);
        Route::get('read', [NotificationsController::class,'read']);
    });
});

Route::get('/reserve', [QueuesController::class, 'store'])->middleware(StudentRoleMiddleware::class);
Route::get('/cancel',[QueuesController::class,'destroy'])->middleware(StudentRoleMiddleware::class);
Route::get('/queue', [QueuesController::class,'show'])->middleware(StudentRoleMiddleware::class);
Route::get('/lib', [LibraryController::class,'index']);

Route::get('/hash/{password}', function ($password) {
    dd(Hash::make($password));
});