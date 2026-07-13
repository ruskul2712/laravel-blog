<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PostService $postService)
    {
        return view('posts.index', $postService->buildFeedData($request, onlyFollowing: false));
    }

    /**
     * "Моя лента" — show posts only from users the current user follows.
     */
    public function followingFeed(Request $request, PostService $postService)
    {
        return view('posts.index', $postService->buildFeedData($request, onlyFollowing: true));
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
    public function store(StorePostRequest $request, PostService $postService)
    {
        $postService->createPost($request->validated(), $request->file('image'), $request->user()->id);

        return redirect()->route('post.feed')->with('status', 'Пост опубликован 🎉');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post, PostService $postService)
    {
        return view('posts.show', $postService->buildShowData($request, $post));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post, PostService $postService)
    {
        $this->authorize('update', $post);

        $post = $postService->updatePost($post, $request->validated(), $request->file('image'));

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
     */
    public function destroy(Post $post, PostService $postService)
    {
        $this->authorize('delete', $post);

        $postService->deletePost($post);

        return response()->json(['deleted' => true]);
    }
}
