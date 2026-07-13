<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Repositories\StoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function __construct(
        private PostRepository $posts,
        private TagRepository $tags,
        private StoryRepository $stories,
        private UserRepository $users,
    ) {}

    /**
     * Build all the data the posts.index view needs, optionally restricted
     * to posts from users the current user follows.
     */
    public function buildFeedData(Request $request, bool $onlyFollowing): array
    {
        $currentUser = $request->user();

        $followingIds = $currentUser ? $currentUser->following()->pluck('users.id')->all() : [];

        $posts = $this->posts->paginateFeed($followingIds, $onlyFollowing);

        $likedPostIds = $currentUser ? $currentUser->likes()->pluck('post_id')->all() : [];
        $bookmarkedPostIds = $currentUser ? $currentUser->bookmarks()->pluck('post_id')->all() : [];
        $repostedPostIds = $currentUser ? $currentUser->reposts()->pluck('post_id')->all() : [];

        $suggestedUsers = $currentUser
            ? $this->users->suggestedFor($currentUser, $followingIds)
            : collect();

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
    public function buildStoryGroups(?User $currentUser): array
    {
        $stories = $this->stories->activeWithUsers();
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
     * Build all the data the posts.show view needs.
     */
    public function buildShowData(Request $request, Post $post): array
    {
        $post = $this->posts->findForShow($post);
        $currentUser = $request->user();

        $isLiked = $currentUser ? $currentUser->likes()->where('post_id', $post->id)->exists() : false;
        $isBookmarked = $currentUser ? $currentUser->bookmarks()->where('post_id', $post->id)->exists() : false;
        $isReposted = $currentUser ? $currentUser->reposts()->where('post_id', $post->id)->exists() : false;

        return compact('post', 'isLiked', 'isBookmarked', 'isReposted');
    }

    public function createPost(array $validated, ?UploadedFile $image, int $userId): Post
    {
        $tagNames = $this->parseTagNames($validated['tags'] ?? null);
        unset($validated['tags']);

        $validated['user_id'] = $userId;

        if ($image) {
            $validated['image'] = $image->store('posts', 'public');
        } else {
            unset($validated['image']);
        }

        $post = $this->posts->create($validated);

        if (! empty($tagNames)) {
            $this->posts->syncTags($post, $this->tags->findOrCreateIdsByNames($tagNames));
        }

        return $post;
    }

    public function updatePost(Post $post, array $validated, ?UploadedFile $image): Post
    {
        if ($image) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $image->store('posts', 'public');
        }

        return $this->posts->update($post, $validated);
    }

    public function deletePost(Post $post): void
    {
        $this->posts->delete($post);
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
}
