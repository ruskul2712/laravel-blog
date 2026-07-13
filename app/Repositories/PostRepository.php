<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{
    public function paginateFeed(array $followingIds, bool $onlyFollowing, int $perPage = 10): LengthAwarePaginator
    {
        $query = Post::with(['user', 'comments.user', 'category', 'tags'])
            ->withCount(['likes', 'comments', 'bookmarks', 'reposts'])
            ->latest();

        if ($onlyFollowing) {
            $query->whereIn('user_id', $followingIds);
        }

        return $query->paginate($perPage);
    }

    /**
     * A user's own posts with like/comment counts, newest first.
     */
    public function forUser(User $user): Collection
    {
        return $user->posts()
            ->withCount(['likes', 'comments'])
            ->latest()
            ->get();
    }

    public function findForShow(Post $post): Post
    {
        $post->load(['user', 'category', 'tags', 'comments.user']);
        $post->loadCount(['likes', 'comments', 'bookmarks', 'reposts']);

        return $post;
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function update(Post $post, array $data): Post
    {
        $post->update($data);

        return $post;
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }

    public function syncTags(Post $post, array $tagIds): void
    {
        $post->tags()->sync($tagIds);
    }
}
