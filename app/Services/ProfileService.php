<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\BookmarkRepository;
use App\Repositories\FollowRepository;
use App\Repositories\PostRepository;
use App\Repositories\RepostRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function __construct(
        private PostRepository $posts,
        private BookmarkRepository $bookmarks,
        private RepostRepository $reposts,
        private FollowRepository $follows,
        private UserRepository $users,
    ) {}

    /**
     * Build all the data the "own profile" page needs.
     */
    public function buildProfileData(User $user): array
    {
        return [
            'user' => $user,
            'posts' => $this->posts->forUser($user),
            'savedPosts' => $this->bookmarks->postsBookmarkedBy($user),
            'repostedPosts' => $this->reposts->postsRepostedBy($user),
            'followersCount' => $this->follows->followersCount($user),
            'followingCount' => $this->follows->followingCount($user),
        ];
    }

    public function updateProfile(User $user, array $validated, ?UploadedFile $avatar): User
    {
        if ($avatar) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $avatar->store('avatars', 'public');
        }

        return $this->users->update($user, $validated);
    }
}
