<?php

use App\Http\Controllers\Admin\AdminKehadiranController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserKehadiranController;
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

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

    Route::group(['middleware' => 'checkRole:admin', 'prefix' => 'admin'], function () {
        Route::prefix('role')->group(function () {
            Route::get('/', [AdminRoleController::class, 'getAllRole']);
            Route::get('/by-id/{id}', [AdminRoleController::class, 'getByIDRole']);
            Route::get('/by-user_id/{user_id}', [AdminRoleController::class, 'getByUserIDRole']);
            Route::post('/create', [AdminRoleController::class, 'createRole']);
            Route::post('/update/{id}', [AdminRoleController::class, 'updateRole']);
            Route::post('/delete/{id}', [AdminRoleController::class, 'deleteRole']);
        });
        Route::prefix('user')->group(function () {
            Route::get('/', [AdminUserController::class, 'getAllUsers']);
            Route::get('/by-uuid/{uuid}', [AdminUserController::class, 'getByUUIDUsers']);
            Route::post('/create', [AdminUserController::class, 'createUser']);
            Route::post('/update/{uuid}', [AdminUserController::class, 'updateUser']);
            Route::post('/delete/{uuid}', [AdminUserController::class, 'deleteUser']);
        });
        Route::prefix('kehadiran')->group(function () {
            Route::get('/', [AdminKehadiranController::class, 'getAllKehadiran']);
            Route::get('/by-id/{id}', [AdminKehadiranController::class, 'getByIDKehadiran']);
            Route::get('/by-user_id/{user_id}', [AdminKehadiranController::class, 'getByUserIDKehadiran']);
            Route::post('/create', [AdminKehadiranController::class, 'createKehadiran']);
            Route::post('/update/{id}', [AdminKehadiranController::class, 'updateKehadiran']);
            Route::post('/delete/{id}', [AdminKehadiranController::class, 'deleteKehadiran']);
        });
    });
    Route::group(['middleware' => 'checkRole:user,admin', 'prefix' => 'user'], function () {
        Route::get('/by-uuid/{uuid}', [UserController::class, 'getByUUIDUsers']);
        Route::post('/update/{uuid}', [UserController::class, 'updateUser']);

        Route::prefix('kehadiran')->group(function () {
            Route::post('/', [UserKehadiranController::class, 'getByUUIDKehadiran']);
            Route::post('/create', [UserKehadiranController::class, 'createKehadiran']);
        });
    });
});
