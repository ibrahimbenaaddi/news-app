<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;



Route::get('/', [ArticleController::class, 'index']);

Route::get('/articles/{article}', function () {
    return view('home');
});

// for admin
Route::get('/admin/edit/articls/{article}', function () {
    return view('home');
});

Route::get('/admin/delete/articls/{article}', function () {
    return view('home');
});

Route::get('/admin', function () {
    return view('home');
});
