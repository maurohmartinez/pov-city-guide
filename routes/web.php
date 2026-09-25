<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('article/{article}', [HomeController::class, 'article'])->name('article');
Route::get('category/{category}', [HomeController::class, 'category'])->name('category');
