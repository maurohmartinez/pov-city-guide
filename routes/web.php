<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('article/{article}', [\App\Http\Controllers\HomeController::class, 'article'])->name('article');
