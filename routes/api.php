<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', 'logout');
            Route::post('refresh-token', 'refreshToken')
                ->middleware('ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value);
        });
    });

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/category', CategoryController::class);
    Route::get('/category/posts/category', [CategoryController::class, 'getAllWithPosts']);
    Route::apiResource('/posts', PostController::class);
    Route::get('/user/posts/me', [PostController::class, 'myPosts']);

    Route::controller(CommentController::class)->group(function () {
        Route::get('/post/{post}/comments', 'index');
        Route::post('/post/{post}/comments', 'store');
        Route::put('/comments/{comment}', 'update');
        Route::delete('/comments/{comment}', 'destroy');
    });
});

