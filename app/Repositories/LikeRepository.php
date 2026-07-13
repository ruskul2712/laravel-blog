<?php

namespace App\Repositories;

use App\Models\Like;
use App\Models\Post;

class LikeRepository
{
    public function find(Post $post, int $userId): ?Like
    {
        return Like::where('post_id', $post->id)->where('user_id', $userId)->first();
    }

    public function create(Post $post, int $userId): Like
    {
        return Like::create(['post_id' => $post->id, 'user_id' => $userId]);
    }

    public function delete(Like $like): void
    {
        $like->delete();
    }

    public function countForPost(Post $post): int
    {
        return $post->likes()->count();
    }
}
