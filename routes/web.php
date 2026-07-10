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
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserInteractionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StoryController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/post', [PostController::class, 'index'])->name('post.feed');
Route::get('/posts/databaza', [DataBazeController::class, 'store']);
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
Route::get('/users/{user}', [UserProfileController::class, 'show'])->name('users.show');
Route::get('/users/{user}/followers', [UserProfileController::class, 'followers'])->name('users.followers');
Route::get('/users/{user}/following', [UserProfileController::class, 'following'])->name('users.following');

Route::middleware('auth')->group(function () {
    Route::get('/hello', [HelloController::class, 'index'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('posts', PostController::class)->except(['index']);

    Route::post('/posts/{post}/like', [PostInteractionController::class, 'toggleLike'])->name('posts.like');
    Route::post('/posts/{post}/bookmark', [PostInteractionController::class, 'toggleBookmark'])->name('posts.bookmark');
    Route::post('/posts/{post}/repost', [PostInteractionController::class, 'toggleRepost'])->name('posts.repost');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/users/{user}/follow', [UserInteractionController::class, 'toggleFollow'])->name('users.follow');

    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    Route::post('/stories/{story}/view', [StoryController::class, 'markViewed'])->name('stories.view');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
