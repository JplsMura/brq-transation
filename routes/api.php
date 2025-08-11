<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;

Route::post('/auth/token', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('jwt.auth')->group(function() {
    Route::apiResource('transactions', TransactionController::class)->only(['index', 'store', 'show']);
});
