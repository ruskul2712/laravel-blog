<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ], [
            'image.image' => 'Файл должен быть изображением.',
            'image.mimes' => 'Поддерживаются форматы: JPG, PNG, WEBP, GIF.',
            'image.max' => 'Максимальный размер файла — 5 МБ.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        // No login system yet, so posts are attributed to the demo user (see ProfileController).
        $validated['user_id'] = User::current()->id;

        Post::create($validated);

        return redirect()->route('post.feed')->with('status', 'Публикация создана 🎉');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * There is no login/authorization system yet, so anyone can edit
     * any post — that restriction is intentionally left out for now.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ], [
            'image.image' => 'Файл должен быть изображением.',
            'image.mimes' => 'Поддерживаются форматы: JPG, PNG, WEBP, GIF.',
            'image.max' => 'Максимальный размер файла — 5 МБ.',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        return response()->json([
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'description' => $post->description,
                'image_url' => $post->imageUrl(),
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * There is no login/authorization system yet, so anyone can delete
     * any post — that restriction is intentionally left out for now.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(['deleted' => true]);
    }
}
