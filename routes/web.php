<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ShortenController;
use App\Http\Controllers\QrController;


Route::get('/', [ShortenController::class, 'index']);

Route::get('/{code}', [RedirectController::class, 'redirect']);
Route::post('/shorten', [ShortenController::class, 'shorten']);

Route::get('/qr/{code}', [QrController::class, 'generate']);