<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments', 'bookmarks', 'reposts'])
            ->latest()
            ->get();

        $currentUser = User::current();

        $likedPostIds = $currentUser->likes()->pluck('post_id')->all();
        $bookmarkedPostIds = $currentUser->bookmarks()->pluck('post_id')->all();
        $repostedPostIds = $currentUser->reposts()->pluck('post_id')->all();

        return view('posts.index', compact('posts', 'likedPostIds', 'bookmarkedPostIds', 'repostedPostIds'));
    }

    public function home()
    {
        $latestPosts = Post::latest()->take(3)->get();
        $postsCount = Post::count();

        return view('posts.home', compact('latestPosts', 'postsCount'));
    }


}
