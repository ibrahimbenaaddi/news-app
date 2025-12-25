<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('articles')->group(function () {

    Route::controller(ArticleController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/title', 'find');
        Route::get('/{id}', 'show')->where('id', '[0-9]+');
    });
});

Route::prefix('admin')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'ability:isAdmin'])->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);
        // for admin
        // Articles operations
        Route::controller(ArticleController::class)->group(function () {
            Route::post('/articles', 'store');
            Route::patch('/edit/articles/{id}', 'update')->where('id', '[0-9]+');
            Route::delete('/delete/articles/{id}', 'destroy')->where('id', '[0-9]+');
        });
    });
});
