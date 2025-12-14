<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Models\Article;
Route::get('/', function (Article $article) {
    return view('home');
});

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
