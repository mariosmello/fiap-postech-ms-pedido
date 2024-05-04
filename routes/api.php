<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::middleware(\App\Http\Middleware\EnsureTokenIsValid::class)->group(function () {
    Route::apiResource('orders', OrderController::class);
});
