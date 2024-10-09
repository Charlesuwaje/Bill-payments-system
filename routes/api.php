<?php

use App\Http\Controllers\Api\Logincontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TransactionController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::get('/', function () {
        return response()->json([
            'status' => 'success',
            'data'   => ['message' => 'Welcome to the Billpayments system API'],
        ]);
    })->name('index');
    Route::post('register', [UserController::class, 'store'])->name('verify.otp');
    Route::post('login', [LoginController::class, 'login'])->name('login');
     Route::middleware(['auth:sanctum'])->group(function () {

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/{user}', [UserController::class, 'show']);
            Route::put('/{user}', [UserController::class, 'update']);
            Route::delete('/{user}', [UserController::class, 'destroy']);
             Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        });
        Route::prefix('transaction')->group(function () {
            Route::post('create', [TransactionController::class, 'store']);
            Route::get('/{transaction}', [TransactionController::class, 'show']);
            Route::get('/', [TransactionController::class, 'index']);
            Route::put('update/{transaction}', [TransactionController::class, 'update']);
            Route::delete('/{transaction}', [TransactionController::class, 'destroy']);
        });
    });
});
