<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/category', CategoryController::class);
Route::get('/category/posts/category', [CategoryController::class, 'getAllWithPosts']);

Route::apiResource('/posts', PostController::class);
