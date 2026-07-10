<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $posts = $user->posts()
            ->withCount(['likes', 'comments'])
            ->latest()
            ->get();

        $savedPosts = $user->bookmarks()
            ->with(['post' => fn ($q) => $q->withCount(['likes', 'comments'])])
            ->latest()
            ->get()
            ->pluck('post')
            ->filter();

        $repostedPosts = $user->reposts()
            ->with(['post' => fn ($q) => $q->withCount(['likes', 'comments'])])
            ->latest()
            ->get()
            ->pluck('post')
            ->filter();

        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('posts.about', compact(
            'user',
            'posts',
            'savedPosts',
            'repostedPosts',
            'followersCount',
            'followingCount'
        ));
    }
}
