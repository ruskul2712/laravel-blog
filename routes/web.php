<?php

use App\Http\Controllers\PostOneController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\PostTwoController;

Route::get('/', [PostOneController::class, 'home']);
Route::get('/post', [PostController::class, 'index']);
Route::get('/my-name', [HelloController::class, 'index']);
Route::get('/posts/create-test', [postController::class, 'index']);
Route::resource('/posts/test', PostTwoController::class);

