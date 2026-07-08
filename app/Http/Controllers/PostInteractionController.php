<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Like;
use App\Models\Post;
use App\Models\Repost;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class PostInteractionController extends Controller
{
    /**
     * Toggle a like on the post for the current user.
     */
    public function toggleLike(Post $post): JsonResponse
    {
        $userId = User::current()->id;
        $like = Like::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $active = false;
        } else {
            Like::create(['post_id' => $post->id, 'user_id' => $userId]);
            $active = true;
        }

        return response()->json([
            'active' => $active,
            'count' => $post->likes()->count(),
        ]);
    }

    /**
     * Toggle a bookmark (save) on the post for the current user.
     */
    public function toggleBookmark(Post $post): JsonResponse
    {
        $userId = User::current()->id;
        $bookmark = Bookmark::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($bookmark) {
            $bookmark->delete();
            $active = false;
        } else {
            Bookmark::create(['post_id' => $post->id, 'user_id' => $userId]);
            $active = true;
        }

        return response()->json([
            'active' => $active,
            'count' => $post->bookmarks()->count(),
        ]);
    }

    /**
     * Toggle a repost (share) on the post for the current user.
     */
    public function toggleRepost(Post $post): JsonResponse
    {
        $userId = User::current()->id;
        $repost = Repost::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($repost) {
            $repost->delete();
            $active = false;
        } else {
            Repost::create(['post_id' => $post->id, 'user_id' => $userId]);
            $active = true;
        }

        return response()->json([
            'active' => $active,
            'count' => $post->reposts()->count(),
        ]);
    }
}
