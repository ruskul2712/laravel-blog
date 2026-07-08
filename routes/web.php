<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\PostTwoController;
use App\Http\Controllers\DataBazeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostInteractionController;


Route::get('/', [HomeController::class, 'index']);
Route::get('/post', [PostController::class, 'index'])->name('post.feed');
Route::get('/hello', [HelloController::class, 'index'])->name('profile.show');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/posts/create-test', [PostController::class, 'index']);
Route::resource('posts', PostTwoController::class);
Route::get('/posts/databaza', [DataBazeController::class, 'store']);

Route::post('/posts/{post}/like', [PostInteractionController::class, 'toggleLike'])->name('posts.like');
Route::post('/posts/{post}/bookmark', [PostInteractionController::class, 'toggleBookmark'])->name('posts.bookmark');
Route::post('/posts/{post}/repost', [PostInteractionController::class, 'toggleRepost'])->name('posts.repost');

Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

