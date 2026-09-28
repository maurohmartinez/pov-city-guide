<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('article/{article}', [HomeController::class, 'article'])->name('article');
Route::get('category/{category}', [HomeController::class, 'category'])->name('category');

Route::get('terms-and-conditions', fn () => view('terms-and-conditions'))->name('terms-and-conditions');
Route::get('privacy-policy', fn () => view('privacy-policy'))->name('privacy-policy');
Route::get('cookie-policy', fn () => view('cookie-policy'))->name('cookie-policy');
