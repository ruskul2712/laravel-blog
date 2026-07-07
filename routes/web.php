<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\PostTwoController;


Route::get('/', [HomeController::class, 'index']);
Route::get('/post', [PostController::class, 'index']);
Route::get('/hello', [HelloController::class, 'index']);
Route::get('/posts/create-test', [PostController::class, 'index']);
Route::resource('/posts/test', PostTwoController::class);

