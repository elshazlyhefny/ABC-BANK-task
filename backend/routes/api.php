<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;

Route::prefix('auth')->group(function () {
  Route::post('register', [AuthController::class, 'register']);
  Route::post('login', [AuthController::class, 'login']);
  Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'me']);
  });
});

Route::middleware('auth:sanctum')->group(function () {
  Route::get('account', [AccountController::class, 'show']);
  Route::get('accounts/transactions', [AccountController::class, 'transactions']);
  Route::post('accounts/deposit', [TransactionController::class, 'deposit']);
  Route::post('accounts/transfer', [TransactionController::class, 'transfer']);
});


