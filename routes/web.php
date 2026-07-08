<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\PostTwoController;
use App\Http\Controllers\DataBazeController;
use App\Http\Controllers\ProfileController;


Route::get('/', [HomeController::class, 'index']);
Route::get('/post', [PostController::class, 'index']);
Route::get('/hello', [HelloController::class, 'index'])->name('profile.show');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/posts/create-test', [PostController::class, 'index']);
Route::resource('/posts/create', PostTwoController::class);
Route::get('/posts/databaza', [DataBazeController::class, 'store']);

