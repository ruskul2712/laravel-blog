<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Repost;
use App\Models\User;
use Illuminate\Support\Collection as SupportCollection;

class RepostRepository
{
    public function find(Post $post, int $userId): ?Repost
    {
        return Repost::where('post_id', $post->id)->where('user_id', $userId)->first();
    }

    /**
     * Posts the given user has reposted, with like/comment counts, newest first.
     */
    public function postsRepostedBy(User $user): SupportCollection
    {
        return $user->reposts()
            ->with(['post' => fn ($q) => $q->withCount(['likes', 'comments'])])
            ->latest()
            ->get()
            ->pluck('post')
            ->filter()
            ->values();
    }

    public function create(Post $post, int $userId): Repost
    {
        return Repost::create(['post_id' => $post->id, 'user_id' => $userId]);
    }

    public function delete(Repost $repost): void
    {
        $repost->delete();
    }

    public function countForPost(Post $post): int
    {
        return $post->reposts()->count();
    }
}
