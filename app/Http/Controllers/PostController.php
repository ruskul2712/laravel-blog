<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();

        return view('posts.index', compact('posts'));
    }

    public function home()
    {
        $latestPosts = Post::latest()->take(3)->get();
        $postsCount = Post::count();

        return view('posts.home', compact('latestPosts', 'postsCount'));
    }
    public function store() {
      Post::create([
          'title' => 'My first post',
          'description' => 'This is my first post',
          'user_id' => 1,
      ]);
      return 'post created';
    }
}
