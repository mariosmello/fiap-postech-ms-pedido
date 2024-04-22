<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    \App\Models\Order::create(['test' => 1]);

    return view('welcome');
});
