<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\TransactionApiController;
use App\Http\Controllers\Api\WalletApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->load('roles');
    })->name('user');

    Route::apiResource('wallets', WalletApiController::class)->only(['index', 'show']);
    Route::apiResource('categories', CategoryApiController::class)->only(['index', 'show']);
    Route::apiResource('transactions', TransactionApiController::class)->only(['index', 'store', 'show']);
});
