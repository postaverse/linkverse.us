<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ShortenController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{code}', [RedirectController::class, 'redirect']);
Route::post('/shorten', [ShortenController::class, 'shorten']);
