<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HelloController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/post', [PostController::class, 'index']);

Route::get('/my-name', [HelloController::class, 'index']);
