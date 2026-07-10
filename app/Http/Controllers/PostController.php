<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Story;
use App\Models\User;
use Illuminate\Support\Facades\Storage;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Post::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments', 'bookmarks', 'reposts'])
            ->latest()
            ->get();

        $currentUser = $request->user();

        $likedPostIds = $currentUser ? $currentUser->likes()->pluck('post_id')->all() : [];
        $bookmarkedPostIds = $currentUser ? $currentUser->bookmarks()->pluck('post_id')->all() : [];
        $repostedPostIds = $currentUser ? $currentUser->reposts()->pluck('post_id')->all() : [];

        $followingIds = [];
        $suggestedUsers = collect();

        if ($currentUser) {
            $followingIds = $currentUser->following()->pluck('users.id')->all();

            $suggestedUsers = User::where('id', '!=', $currentUser->id)
                ->whereNotIn('id', $followingIds)
                ->latest()
                ->limit(3)
                ->get();
        }

        [$ownStoryGroup, $otherStoryGroups] = $this->buildStoryGroups($currentUser);

        return view('posts.index', compact(
            'posts',
            'likedPostIds',
            'bookmarkedPostIds',
            'repostedPostIds',
            'followingIds',
            'suggestedUsers',
            'ownStoryGroup',
            'otherStoryGroups'
        ));
    }

    /**
     * Group active (< 24h old) stories by author, splitting the current
     * user's own stories out from everyone else's. Others are ordered
     * with unseen story rings first, Instagram-style.
     */
    private function buildStoryGroups(?User $currentUser): array
    {
        $stories = Story::with('user')->active()->orderBy('created_at')->get();
        $seenStoryIds = $currentUser ? $currentUser->seenStories()->pluck('stories.id')->all() : [];

        $ownGroup = null;
        $otherGroups = collect();

        foreach ($stories->groupBy('user_id') as $userId => $userStories) {
            $group = [
                'user' => $userStories->first()->user,
                'items' => $userStories->values(),
                'allSeen' => $userStories->every(fn ($story) => in_array($story->id, $seenStoryIds)),
            ];

            if ($currentUser && (int) $userId === $currentUser->id) {
                $ownGroup = $group;
            } else {
                $otherGroups->push($group);
            }
        }

        $otherGroups = $otherGroups->sortBy(fn ($group) => $group['allSeen'] ? 1 : 0)->values();

        return [$ownGroup, $otherGroups];
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

        $validated['user_id'] = $request->user()->id;

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
