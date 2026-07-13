<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\UserFollowed;
use App\Repositories\FollowRepository;

class FollowService
{
    public function __construct(private FollowRepository $follows) {}

    /**
     * Toggle a follow relationship from $currentUser onto $target.
     *
     * Returns null if the user tried to follow themselves — the controller
     * decides how to turn that into an HTTP response.
     */
    public function toggleFollow(User $currentUser, User $target): ?array
    {
        if ($currentUser->is($target)) {
            return null;
        }

        if ($this->follows->isFollowing($currentUser, $target)) {
            $this->follows->unfollow($currentUser, $target);
            $active = false;
        } else {
            $this->follows->follow($currentUser, $target);
            $target->notify(new UserFollowed($currentUser));
            $active = true;
        }

        return [
            'active' => $active,
            'followers_count' => $this->follows->followersCount($target),
            'following_count' => $this->follows->followingCount($currentUser),
        ];
    }
}
