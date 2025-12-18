<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::controller(ArticleController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/title', 'find')->name('find');
    Route::get('/articles/{article}', 'show')->name('show');
});

Route::prefix('admin')->group(function () {
    
    // login & logout 
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'loginPanel')->name('login.form');
        Route::post('/login', 'login')->name('login');
        Route::post('/logout','logout')->name('logout');
    });

    Route::prefix('dashboard')->group(function(){
        // for admin
        // Articles operations
        Route::controller(ArticleController::class)->group(function (){
            Route::get('/','index')->name('dashboard');
            Route::get('/add/article','create')->name('add');
            Route::post('/add/article','store')->name('store');
            Route::get('/edit/articles/{article}', 'edit' )->name('edit');
            Route::patch('/edit/articles/{article}', 'update' )->name('update');
            Route::delete('/delete/articls/{article}','destroy')->name('delete');
        });
        });
});
