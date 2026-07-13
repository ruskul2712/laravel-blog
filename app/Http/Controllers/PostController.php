<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\Story;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('posts.index', $this->buildFeedData($request, onlyFollowing: false));
    }

    /**
     * "Моя лента" — show posts only from users the current user follows.
     */
    public function followingFeed(Request $request)
    {
        return view('posts.index', $this->buildFeedData($request, onlyFollowing: true));
    }

    /**
     * Build all the data the posts.index view needs, optionally restricted
     * to posts from users the current user follows.
     */
    private function buildFeedData(Request $request, bool $onlyFollowing): array
    {
        $currentUser = $request->user();

        $followingIds = $currentUser ? $currentUser->following()->pluck('users.id')->all() : [];

        $postsQuery = Post::with(['user', 'comments.user', 'category', 'tags'])
            ->withCount(['likes', 'comments', 'bookmarks', 'reposts'])
            ->latest();

        if ($onlyFollowing) {
            $postsQuery->whereIn('user_id', $followingIds);
        }

        $posts = $postsQuery->get();

        $likedPostIds = $currentUser ? $currentUser->likes()->pluck('post_id')->all() : [];
        $bookmarkedPostIds = $currentUser ? $currentUser->bookmarks()->pluck('post_id')->all() : [];
        $repostedPostIds = $currentUser ? $currentUser->reposts()->pluck('post_id')->all() : [];

        $suggestedUsers = collect();

        if ($currentUser) {
            $suggestedUsers = User::where('id', '!=', $currentUser->id)
                ->whereNotIn('id', $followingIds)
                ->latest()
                ->limit(3)
                ->get();
        }

        [$ownStoryGroup, $otherStoryGroups] = $this->buildStoryGroups($currentUser);

        $categories = Category::orderBy('name')->get();

        return compact(
            'posts',
            'likedPostIds',
            'bookmarkedPostIds',
            'repostedPostIds',
            'followingIds',
            'suggestedUsers',
            'ownStoryGroup',
            'otherStoryGroups',
            'categories',
            'onlyFollowing'
        );
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
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $tagNames = $this->parseTagNames($validated['tags'] ?? null);
        unset($validated['tags']);

        $validated['user_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        } else {
            unset($validated['image']);
        }

        $post = Post::create($validated);

        if (! empty($tagNames)) {
            $tagIds = collect($tagNames)->map(
                fn (string $name) => Tag::firstOrCreate(['name' => $name])->id
            );
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('post.feed')->with('status', 'Пост опубликован 🎉');
    }

    /**
     * Split a comma-separated "tags" input into a clean list of unique names.
     */
    private function parseTagNames(?string $tags): array
    {
        if (! $tags) {
            return [];
        }

        return collect(explode(',', $tags))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        $post->load(['user', 'category', 'tags', 'comments.user']);
        $post->loadCount(['likes', 'comments', 'bookmarks', 'reposts']);

        $currentUser = $request->user();

        $isLiked = $currentUser ? $currentUser->likes()->where('post_id', $post->id)->exists() : false;
        $isBookmarked = $currentUser ? $currentUser->bookmarks()->where('post_id', $post->id)->exists() : false;
        $isReposted = $currentUser ? $currentUser->reposts()->where('post_id', $post->id)->exists() : false;

        return view('posts.show', compact('post', 'isLiked', 'isBookmarked', 'isReposted'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post){
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }
    /**
     * Update the specified resource in storage.
     *
     * There is no login/authorization system yet, so anyone can edit
     * any post — that restriction is intentionally left out for now.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validated();

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
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(['deleted' => true]);
    }
}
