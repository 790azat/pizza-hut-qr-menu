<?php

use App\Http\Controllers\InstallController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

// Public routes run without sessions/cookies so Vercel's CDN can cache them.
Route::get('/', [MenuController::class, 'show'])->name('menu');
Route::get('/img/{id}', [MenuController::class, 'image'])->whereNumber('id')->name('image');
Route::get('/install', InstallController::class);
