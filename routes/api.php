<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/transactions', [TransactionController::class, 'store'])->middleware('idempotency');
    Route::get('/transactions/history', [TransactionController::class, 'history']);
});
