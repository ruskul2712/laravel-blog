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

        return view('posts.about', compact('user', 'posts'));
    }
}
