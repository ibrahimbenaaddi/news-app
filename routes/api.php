<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthJWTController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('articles')->middleware(['throttle:api-get-articles'])->group(function () {
        Route::controller(ArticleController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show')->where('id', '[0-9]+');
        });
    });
    // Admin
    Route::middleware(['throttle:api-admin-article'])->group(function () {

        Route::prefix('jwt/admin')->group(function () {
            Route::middleware('auth:api')->group(function () {
                Route::post('/logout', [AuthJWTController::class, 'logout']);
                Route::controller(ArticleController::class)->group(function () {
                    Route::post('/articles', 'store');
                    Route::patch('/articles/{id}', 'update')->where('id', '[0-9]+');
                    Route::delete('/articles/{id}', 'destroy')->where('id', '[0-9]+');
                });
            });
            Route::post('/login', [AuthJWTController::class, 'login'])->middleware(['throttle:api-login']);
            // check Refresh Token and send it 
            Route::middleware('CheckTokenJWT')->group(function () {
                Route::get('/amIAuth', function () {
                    return response()->json(['status' => true], 200);
                });
                Route::get('/refreshToken', [AuthJWTController::class, 'refreshToken']);
            });
        });

        // Sanctum
        Route::prefix('admin')->group(function () {
            Route::post('/login', [AuthController::class, 'login'])->middleware(['throttle:api-login']);
            Route::middleware('auth:sanctum')->group(function () {
                Route::get('/amIAuth', function () {
                    return response()->json(['status' => true], 200);
                });
                Route::post('/logout', [AuthController::class, 'logout']);
                Route::controller(ArticleController::class)->group(function () {
                    Route::post('/articles', 'store');
                    Route::patch('/articles/{id}', 'update')->where('id', '[0-9]+');
                    Route::delete('/articles/{id}', 'destroy')->where('id', '[0-9]+');
                });
            });
        });
    });
});
