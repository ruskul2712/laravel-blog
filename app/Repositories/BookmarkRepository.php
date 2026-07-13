<?php

namespace App\Repositories;

use App\Models\Bookmark;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Collection as SupportCollection;

class BookmarkRepository
{
    public function find(Post $post, int $userId): ?Bookmark
    {
        return Bookmark::where('post_id', $post->id)->where('user_id', $userId)->first();
    }

    /**
     * Posts the given user has bookmarked, with like/comment counts, newest first.
     */
    public function postsBookmarkedBy(User $user): SupportCollection
    {
        return $user->bookmarks()
            ->with(['post' => fn ($q) => $q->withCount(['likes', 'comments'])])
            ->latest()
            ->get()
            ->pluck('post')
            ->filter()
            ->values();
    }

    public function create(Post $post, int $userId): Bookmark
    {
        return Bookmark::create(['post_id' => $post->id, 'user_id' => $userId]);
    }

    public function delete(Bookmark $bookmark): void
    {
        $bookmark->delete();
    }

    public function countForPost(Post $post): int
    {
        return $post->bookmarks()->count();
    }
}
