<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class PostService
{
    public function buildFeedData(Request $request, bool $onlyFollowing): array
    {
        $currentUser = $request->user();

        $followingIds = $currentUser ? $currentUser->following()->pluck('users.id')->all() : [];

        $postsQuery = Post::with(['user', 'comments.user', 'category', 'tags'])
            ->withCount(['likes', 'comments', 'bookmarks', 'reposts'])
            ->latest();

        if ($onlyFollowing) {
            $postsQuery->whereIn('user_id', $followingIds);
        }

        $posts = $postsQuery->paginate(10);

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
}
