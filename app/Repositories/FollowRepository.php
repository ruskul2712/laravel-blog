<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class FollowRepository
{
    /**
     * Users who follow the given user, most recently followed first.
     */
    public function followersOf(User $user): Collection
    {
        return $user->followers()->orderByPivot('created_at', 'desc')->get();
    }

    /**
     * Users the given user follows, most recently followed first.
     */
    public function followingOf(User $user): Collection
    {
        return $user->following()->orderByPivot('created_at', 'desc')->get();
    }

    public function isFollowing(User $follower, User $target): bool
    {
        return $follower->isFollowing($target);
    }

    public function follow(User $follower, User $target): void
    {
        $follower->following()->attach($target->id);
    }

    public function unfollow(User $follower, User $target): void
    {
        $follower->following()->detach($target->id);
    }

    public function followersCount(User $user): int
    {
        return $user->followers()->count();
    }

    public function followingCount(User $user): int
    {
        return $user->following()->count();
    }
}
