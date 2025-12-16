<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;



Route::get('/', [ArticleController::class, 'index'])->name('home');

Route::get('/title',[ArticleController::class, 'find'])->name('find');

Route::get('/articles/{article}',[ArticleController::class, 'show'])->name('show');

// for admin

Route::get('/admin/login',[AuthController::class,'loginPanel'])->name('login.form');
Route::post('/admin/login',[AuthController::class,'login'])->name('login');



Route::get('/admin/edit/articls/{article}', function () {
    return view('home');
});

Route::get('/admin/delete/articls/{article}', function () {
    return view('home');
});

Route::get('/admin', function () {
    return view('home');
});
