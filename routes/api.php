<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'getAllUsers']);
    Route::get('/by-uuid/{uuid}', [UserController::class, 'getByUUIDUsers']);
    Route::post('/create', [UserController::class, 'createUser']);
    Route::post('/update/{uuid}', [UserController::class, 'updateUser']);
    Route::post('/delete/{uuid}', [UserController::class, 'deleteUser']);
});
Route::prefix('kehadiran')->group(function () {
    Route::get('/', [KehadiranController::class, 'getAllKehadiran']);
    Route::get('/by-id/{id}', [KehadiranController::class, 'getByIDKehadirans']);
    Route::get('/by-user_id/{user_id}', [KehadiranController::class, 'getByUserIDKehadirans']);
    Route::post('/create', [KehadiranController::class, 'createKehadiran']);
    Route::post('/update/{id}', [KehadiranController::class, 'updateKehadiran']);
    Route::post('/delete/{id}', [KehadiranController::class, 'deleteKehadiran']);
});

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});
