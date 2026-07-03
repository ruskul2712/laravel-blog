<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HelloController;

Route::get('/', [PostController::class, 'home']);
Route::get('/post', [PostController::class, 'index']);

Route::get('/my-name', [HelloController::class, 'index']);
