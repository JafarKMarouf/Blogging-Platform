<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::controller(CategoryController::class)
    ->prefix('categories')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{categoryId}', 'show');
        Route::post('/', 'store');
        Route::put('/{categoryId}', 'update');
        Route::delete('/{category}', 'destroy');
        Route::get('/posts/category', 'getAllWithPosts');
    });
