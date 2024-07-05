<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ShortenController;


Route::get('/', [ShortenController::class, 'index']);

Route::get('/{code}', [RedirectController::class, 'redirect']);
Route::post('/shorten', [ShortenController::class, 'shorten']);
