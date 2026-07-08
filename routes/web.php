<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DataBazeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostInteractionController;
use App\Http\Controllers\AuthController;


Route::get('/', [HomeController::class, 'index']);
Route::get('/post', [PostController::class, 'index'])->name('post.feed');
Route::get('/hello', [HelloController::class, 'index'])->name('profile.show');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::resource('posts', PostController::class);
Route::get('/posts/databaza', [DataBazeController::class, 'store']);

Route::post('/posts/{post}/like', [PostInteractionController::class, 'toggleLike'])->name('posts.like');
Route::post('/posts/{post}/bookmark', [PostInteractionController::class, 'toggleBookmark'])->name('posts.bookmark');
Route::post('/posts/{post}/repost', [PostInteractionController::class, 'toggleRepost'])->name('posts.repost');

Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

Route::get('/register', [AuthController::class, 'showRegister'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
