<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\Notifications\PostLiked;
use App\Repositories\BookmarkRepository;
use App\Repositories\LikeRepository;
use App\Repositories\RepostRepository;

class PostInteractionService
{
    public function __construct(
        private LikeRepository $likes,
        private BookmarkRepository $bookmarks,
        private RepostRepository $reposts,
    ) {}

    /**
     * Toggle a like on the post for the given user, notifying the author
     * the first time (but never for liking your own post).
     */
    public function toggleLike(Post $post, User $user): array
    {
        $like = $this->likes->find($post, $user->id);

        if ($like) {
            $this->likes->delete($like);
            $active = false;
        } else {
            $this->likes->create($post, $user->id);
            $active = true;

            if ($post->user && $post->user->id !== $user->id) {
                $post->user->notify(new PostLiked($user, $post));
            }
        }

        return ['active' => $active, 'count' => $this->likes->countForPost($post)];
    }

    public function toggleBookmark(Post $post, User $user): array
    {
        $bookmark = $this->bookmarks->find($post, $user->id);

        if ($bookmark) {
            $this->bookmarks->delete($bookmark);
            $active = false;
        } else {
            $this->bookmarks->create($post, $user->id);
            $active = true;
        }

        return ['active' => $active, 'count' => $this->bookmarks->countForPost($post)];
    }

    public function toggleRepost(Post $post, User $user): array
    {
        $repost = $this->reposts->find($post, $user->id);

        if ($repost) {
            $this->reposts->delete($repost);
            $active = false;
        } else {
            $this->reposts->create($post, $user->id);
            $active = true;
        }

        return ['active' => $active, 'count' => $this->reposts->countForPost($post)];
    }
}
